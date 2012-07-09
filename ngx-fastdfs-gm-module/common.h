/*
 * File:   common.h
 * Author: mohadoop
 *
 * Created on 2012年7月9日, 下午4:14
 */

#ifndef COMMON_H
#define	COMMON_H

#include <time.h>
#include "tracker_types.h"

#ifndef HTTP_BADREQUEST
#define HTTP_BADREQUEST         400
#endif

#ifndef HTTP_OK
#define HTTP_OK                    200
#endif

#ifdef	__cplusplus
extern "C" {
#endif

    struct fdfs_http_response;
    typedef void (*FDFSOutputHeaders)(void *arg, struct fdfs_http_response *pResponse);
    typedef int (*FDFSSendFile)(void *arg, const char *filename, const int filename_len, const int64_t file_offset, const int64_t download_bytes);
    typedef int (*FDFSSendReplyChunk)(void *arg, const bool last_buff, const char *buff, const int size);
    typedef int (*FDFSProxyHandler)(void *arg, const char *dest_ip_addr);

    struct fdfs_http_range {
        int64_t start;
        int64_t end;
    };

    struct fdfs_http_context {
        int server_port;
        bool header_only;
        bool if_range;
        struct fdfs_http_range range;
        char if_modified_since[32];
        char *url;
        void *arg; //for callback
        FDFSOutputHeaders output_headers;
        FDFSSendFile send_file; //nginx send file
        FDFSSendReplyChunk send_reply_chunk;
        FDFSProxyHandler proxy_handler; //nginx proxy handler
    };


    /**
     * init function
     * params:
     * return: 0 success, !=0 fail, return the error code
     *
    int fdfs_mod_init();

    /**
     * parse range parameter
     * params:
     *	value the range value
     *	rang the range object, store start and end position
     * return: 0 success, !=0 fail, return the error code
     */
    int fdfs_parse_range(const char *value, struct fdfs_http_range *range);

    /**
     * http request handler
     * params:
     *	pContext the context
     * return: http status code, HTTP_OK success, != HTTP_OK fail
     */
    int fdfs_http_request_handler(struct fdfs_http_context *pContext);

#ifdef	__cplusplus
}
#endif

#endif	/* COMMON_H */

