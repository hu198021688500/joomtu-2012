#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include <limits.h>
#include <time.h>
#include <unistd.h>
//#include "fdfs_define.h"
//#include "logger.h"
//#include "shared_func.h"
//#include "fdfs_global.h"
//#include "sockopt.h"
//#include "http_func.h"
//#include "fdfs_http_shared.h"
//#include "fdfs_client.h"
//#include "local_ip_func.h"
//#include "trunk_shared.h"

#include "common.h"

int fdfs_mod_init() {

    return 0;
}

int fdfs_parse_range(const char *value, struct fdfs_http_range *range) {

    return 0;
}

int fdfs_http_request_handler(struct fdfs_http_context *pContext) {
    return HTTP_OK;
}