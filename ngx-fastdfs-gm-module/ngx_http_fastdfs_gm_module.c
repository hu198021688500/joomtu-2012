#include <ngx_config.h>
//#include <ngx_core.h>
#include <ngx_http.h>
//#include <sys/types.h>
//#include <unistd.h>
#include "common.h"

static char *ngx_http_fastdfs_gm_set(ngx_conf_t *cf, ngx_command_t *cmd, void *conf);
static ngx_int_t ngx_http_fastdfs_gm_handler(ngx_http_request_t *r);
static void fdfs_output_headers(void *arg, struct fdfs_http_response *pResponse);
static int fdfs_send_file(void *arg, const char *filename, const int filename_len, const int64_t file_offset, const int64_t download_bytes);
static int fdfs_send_reply_chunk(void *arg, const bool last_buf, const char *buff, const int size);
static int ngx_http_fastdfs_proxy_handler(void *arg, const char *dest_ip_addr);

//模块的配置结构体，模块的配置结构体定义有三种，分别是全局、主机和位置的配置结构体。
//大多数HTTP模块仅仅需要一个位置的配置结构提。名称约定是这样的：
//ngx_http_<module name>_(main|srv|loc)_conf_t

typedef struct {
    ngx_http_upstream_conf_t upstream;
    ngx_uint_t headers_hash_max_size;
    ngx_uint_t headers_hash_bucket_size;
} ngx_http_fastdfds_gm_loc_conf_t;

//模块的指令出现在静态数组ngx_command_t
static ngx_command_t ngx_http_fastdfs_gm_commands[] = {
    {
        ngx_string("ngx_fastdfs_module"), //指令的字符窜即名称
        //NGX_HTTP_MAIN_CONF:指令出现在全局配置部分是合法的
        //NGX_HTTP_SRV_CONF:指令在主机配置部分出现是合法的
        //NGX_HTTP_LOC_CONF:指令在位置配置部分出现是合法的
        //NGX_HTTP_UPS_CONF:指令在上游服务器配置部分出现是合法的
        //NGX_CONG_NOARGS:指令没有参数
        //NGX_CONG_TAKE1.....NGX_CONF_TAKE7:指令读入1-7个参数
        //NGX_CONF_FLAG:指令读入一个布尔型数据
        //NGX_CONF_1MORE:指令至少读入1个参数
        //NGX_CONF_2MORE:指令至少读入2个参数
        NGX_HTTP_LOC_CONF | NGX_CONF_NOARGS, //标识的集合
        //设定模块的配置，这个函数会转化读入指令传进来的参数，然后将合适的值保存到配置结构题
        //*(*set)(ngx_conf_t * cf,ngx_command_t * cmd, void *conf);
        //第一个参数指向ngx_conf_t结构体，包含从配置文件中指令传过来的参数
        //第二个参数指向前ngx_command_t结构体指针
        //第三个参数指向自定义模块配置结构体指针
        ngx_http_fastdfs_gm_set,
        NGX_HTTP_LOC_CONF_OFFSET,
        0,
        NULL
    },

    ngx_null_command
};

static char *ngx_http_fastdfs_gm_set(ngx_conf_t *cf, ngx_command_t *cmd, void *conf) {
    int result;
    ngx_http_core_loc_conf_t *clcf = ngx_http_conf_get_module_loc_conf(cf, ngx_http_core_module);
    fprintf(stderr, "ngx_http_fastdfs_gm_set pid=%d\n", getpid());
    /* register hanlder */
    clcf->handler = ngx_http_fastdfs_gm_handler;
    if ((result = fdfs_mod_init()) != 0) {
        return NGX_CONF_ERROR;
    }
    return NGX_CONF_OK;
}

static ngx_int_t ngx_http_fastdfs_gm_handler(ngx_http_request_t *r) {
    struct fdfs_http_context context;
    ngx_int_t rc;
    char url[4096];
    char *p;
    if (!(r->method & (NGX_HTTP_GET | NGX_HTTP_HEAD))) {
        return NGX_HTTP_NOT_ALLOWED;
    }
    rc = ngx_http_discard_request_body(r);
    if (rc != NGX_OK && rc != NGX_AGAIN) {
        return rc;
    }
    if (r->uri.len + r->args.len + 1 >= sizeof (url)) {
        ngx_log_error(NGX_LOG_ERR, r->connection->log, 0, "url too long, exceeds %d bytes!", (int) sizeof (url));
        return HTTP_BADREQUEST;
    }
    p = url;
    memcpy(p, r->uri.data, r->uri.len);
    p += r->uri.len;
    if (r->args.len > 0) {
        *p++ = '?';
        memcpy(p, r->args.data, r->args.len);
        p += r->args.len;
    }
    *p = '\0';

    memset(&context, 0, sizeof (context));
    context.arg = r;
    context.header_only = r->header_only;
    context.url = url;
    context.output_headers = fdfs_output_headers;
    context.send_file = fdfs_send_file;
    context.send_reply_chunk = fdfs_send_reply_chunk;
    context.proxy_handler = ngx_http_fastdfs_proxy_handler;
    context.server_port = ntohs(((struct sockaddr_in *) r->connection->local_sockaddr)->sin_port);

    if (r->headers_in.if_modified_since != NULL) {
        if (r->headers_in.if_modified_since->value.len < sizeof (context.if_modified_since)) {
            memcpy(context.if_modified_since, r->headers_in.if_modified_since->value.data, r->headers_in.if_modified_since->value.len);
        }
    }
    if (r->headers_in.range != NULL) {
        char buff[64];
        if (r->headers_in.range->value.len >= sizeof (buff)) {
            ngx_log_error(NGX_LOG_ERR, r->connection->log, 0, \
				"bad request, range length: %d exceeds buff " \
				"size: %d, range: %*s", \
				r->headers_in.range->value.len, \
				(int) sizeof (buff), \
				r->headers_in.range->value.len, \
				r->headers_in.range->value.data);
            return NGX_HTTP_BAD_REQUEST;
        }
        memcpy(buff, r->headers_in.range->value.data, r->headers_in.range->value.len);
        *(buff + r->headers_in.range->value.len) = '\0';
        if (fdfs_parse_range(buff, &(context.range)) != 0) {
            ngx_log_error(NGX_LOG_ERR, r->connection->log, 0, "bad request, invalid range: %s", buff);
            return NGX_HTTP_BAD_REQUEST;
        }
        context.if_range = true;
    }
    return fdfs_http_request_handler(&context);
}

static void fdfs_output_headers(void *arg, struct fdfs_http_response *pResponse) {

}

static int fdfs_send_file(void *arg, const char *filename, const int filename_len, const int64_t file_offset, const int64_t download_bytes) {
    return 0;
}

static int fdfs_send_reply_chunk(void *arg, const bool last_buf, const char *buff, const int size) {
    return 0;
}

static int ngx_http_fastdfs_proxy_handler(void *arg, const char *dest_ip_addr) {
    return NGX_DONE;
}