<?php

/**
 * 2012-8-15 17:39:50 UTF-8
 * @package
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011-2015
 * @license ()
 *
 * $Id$
 *
 */

/**
 * Description of MyFastDFS
 */
class MyFastDFS {

    private $__error;

    public function __construct() {
        //fastdfs_tracker_make_all_connections();
    }

    public function __destruct() {
        //fastdfs_tracker_close_all_connections();
    }

    public function test() {

    }

    public function uploadSlave($local_filename, $master_filename, $prefix_name, $file_ext_name = null, $meta_list = array()) {
        $tracker = $this->_getTrackerCon();
        $storage = $this->_getStorageCon();
        return fastdfs_storage_upload_slave_by_filename1($local_filename, $master_filename, $prefix_name, $file_ext_name, $meta_list, $tracker, $storage);
    }

    /**
     * 上传本地文件到FastDFS集群
     * @param string $fileName the local filename
     * @param string $fileExtName optional the file extension name, do not include dot(.)
     * @param array $metaList optional meta data assoc array, such as array('width'=>1024, 'height'=>768)
     * @param string $groupName optional specify the group name to store the file
     * @return array return assoc array for success, false for error. the returned array includes elements: group_name and filename
     */
    public function upload($fileName, $fileExtName = null, $metaList = array(), $groupName = null) {
        $tracker = fastdfs_tracker_get_connection();
        //$tracker = fastdfs_connect_server($ip, $port);
        $storage = fastdfs_tracker_query_storage_store();
        //$storage = fastdfs_connect_server($ip, $port);
        $result = fastdfs_storage_upload_by_filename($fileName, $fileExtName, $metaList, $groupName, $tracker, $storage);
        fastdfs_disconnect_server($tracker);
        fastdfs_disconnect_server($storage);
        return $result;
    }

    /**
     * 从缓冲区上传内容到FastDFS集群
     * Enter description here ...
     * @param string $buff the file content
     * @param string $fileExtName optional the file extension name, do not include dot(.)
     * @param array $metaList optional meta data assoc array, such as array('width'=>1024, 'height'=>768)
     * @param string $groupName optional specify the group name to store the file
     * @return return assoc array for success, false for error. the returned array includes elements: group_name and filename
     */
    public function buffToUpload($buff, $fileExtName = null, $metaList = array(), $groupName = null) {
        $tracker = $this->_getTrackerCon();
        $storage = $this->_getStorageCon();
        return fastdfs_storage_upload_by_filebuff($buff, $fileExtName, $metaList, $groupName, $tracker, $storage);
    }

    /**
     *
     * @param array $callback the callback assoc array, must have keys:
     * 		callback  - the php callback function name callback function prototype as:
     * 			function upload_file_callback($sock, $args)
     * 			file_size - the file size
     * 			args      - use argument for callback function
     * @param string $fileExtName optional the file extension name, do not include dot(.)
     * @param array $metaList optional meta data assoc array, such as array('width'=>1024, 'height'=>768)
     * @param string $groupName optional specify the group name to store the file
     * @return return assoc array for success, false for error. the returned array includes elements: group_name and filename
     */
    public function uploadCallback($callback, $fileExtName = null, $metaList = array(), $groupName = null) {
        $tracker = $this->_getTrackerCon();
        $storage = $this->_getStorageCon();
        return fastdfs_storage_upload_by_callback($callback, $fileExtName, $metaList, $groupName, $tracker, $storage);
    }

    /**
     * 从一个包含文件信息的数组中得到文件Id
     * @param array $fileInfo
     * @return string
     */
    public function getFileId($fileInfo) {
        return $fileInfo['group_name'] . FDFS_FILE_ID_SEPERATOR . $fileInfo['filename'];
    }

    /**
     * 获取远程文件的信息
     * @param string $fileId the file id of the file
     * @return array
     */
    public function getFileInfo($fileId) {
        return fastdfs_get_file_info1($fileId);
    }

    /**
     * 下载文件到本地
     * @param string $fileId the file id of the file
     * @param string $localFilename the local filename to save the file content
     * @return boolean return true for success, false for error
     */
    public function download($fileId, $localFilename) {
        //$tracker = $this->_getTrackerCon();
        //$storage = $this->_getStorageCon();
        return fastdfs_storage_download_file_to_file1($fileId, $localFilename);
    }

    /**
     * 下载文件内容到缓冲区
     * @param string $fileId the file id of the file
     * @return boolean return the file content for success, false for error
     */
    public function downloadToBuff($fileId) {
        return fastdfs_storage_download_file_to_buff($fileId);
    }

    /**
     *
     * @param string $fileId
     * @param array $callback the sameto upload
     * @return boolean return true for success, false for error
     */
    public function downloadCallback($fileId, $callback) {
        return fastdfs_storage_download_file_to_callback1($fileId, $callback);
    }

    /**
     * 删除文件
     * @param string $fileId
     * @return boolean
     */
    public function delete($fileId) {
        return fastdfs_storage_delete_file1($fileId);
    }

    /**
     * 获取错误信息
     */
    public function getError() {
        $this->__error = fastdfs_get_last_error_no() . " " . fastdfs_get_last_error_info();
    }

    /**
     * 设置错误信息
     */
    private function setError($error) {
        $this->__error = $error;
    }

    /**
     * 获取tracker服务器连接
     * @param string $ip
     * @param int $port
     * @return array
     */
    private function _getTrackerCon($ip = null, $port = null) {
        if (is_null($ip) || is_null($port)) {
            $tracker = fastdfs_tracker_get_connection();
        } else {
            $tracker = fastdfs_connect_server($ip, $port);
        }
        return $tracker;
    }

    /**
     * 获取storage服务器连接
     * @param string $ip
     * @param int $port
     * @return array
     */
    private function _getStorageCon($ip = null, $port = null) {
        if (is_null($ip) || is_null($port)) {
            $storage = fastdfs_tracker_query_storage_store();
        } else {
            $storage = fastdfs_connect_server($ip, $port);
        }
        if (!$storage) {
            $this->setError();
        }
        return $storage;
    }

    /**
     * 释放服务器连接
     * @param array $server
     */
    private function _disCon($server) {
        return fastdfs_disconnect_server($server);
    }

}

/*
fastdfs_client_version

fastdfs_get_last_error_no
fastdfs_get_last_error_info

fastdfs_http_gen_token

fastdfs_get_file_info
fastdfs_get_file_info1

fastdfs_send_data
fastdfs_gen_slave_filename

fastdfs_storage_upload_by_filename
fastdfs_storage_upload_by_filename1
fastdfs_storage_upload_by_filebuff
fastdfs_storage_upload_by_filebuff1
fastdfs_storage_upload_by_callback
fastdfs_storage_upload_by_callback1

fastdfs_storage_upload_slave_by_filename
fastdfs_storage_upload_slave_by_filename1
fastdfs_storage_upload_slave_by_filebuff
fastdfs_storage_upload_slave_by_filebuff1
fastdfs_storage_upload_slave_by_callback
fastdfs_storage_upload_slave_by_callback1

fastdfs_storage_upload_appender_by_filename
fastdfs_storage_upload_appender_by_filename1
fastdfs_storage_upload_appender_by_filebuff
fastdfs_storage_upload_appender_by_filebuff1
fastdfs_storage_upload_appender_by_callback
fastdfs_storage_upload_appender_by_callback1

fastdfs_storage_append_by_filename
fastdfs_storage_upload_by_filename1
fastdfs_storage_append_by_filebuff
fastdfs_storage_append_by_filebuff1
fastdfs_storage_append_by_callback
fastdfs_storage_append_by_callback1

fastdfs_storage_download_file_to_buff
fastdfs_storage_download_file_to_buff1
fastdfs_storage_download_file_to_file
fastdfs_storage_download_file_to_file1
fastdfs_storage_download_file_to_callback
fastdfs_storage_download_file_to_callback1

fastdfs_storage_set_metadata
fastdfs_storage_set_metadata1
fastdfs_storage_get_metadata
fastdfs_storage_get_metadata1

fastdfs_tracker_get_connection
fastdfs_connect_server
fastdfs_disconnect_server
fastdfs_active_test

fastdfs_tracker_list_groups

fastdfs_tracker_make_all_connections
fastdfs_tracker_close_all_connections

fastdfs_tracker_query_storage_store
fastdfs_tracker_query_storage_store_list
fastdfs_tracker_query_storage_update
fastdfs_tracker_query_storage_update1
fastdfs_tracker_query_storage_fetch
fastdfs_tracker_query_storage_fetch1
fastdfs_tracker_query_storage_list
fastdfs_tracker_query_storage_list1

fastdfs_storage_delete_file
fastdfs_storage_delete_file1
fastdfs_tracker_delete_storage

1.$tracker = fastdfs_tracker_get_connection();获取一个与tracker的连接
2.fastdfs_active_test($tracker)测试当前连接是否处于激活状态
3.$server = fastdfs_connect_server($tracker['ip_addr'], $tracker['port']); 连接到tracker
4.$storage = fastdfs_tracker_query_storage_store();获取一个storage服务器连接
5.$server = fastdfs_connect_server($storage['ip_addr'], $storage['port']);连接到storage服务器
6.fastdfs_active_test($server)连接是否激活
7.$file_info = fastdfs_storage_upload_by_filename("/usr/include/stdio.h", null, array(), null, $tracker, $storage);上传文件
8.fastdfs_storage_download_file_to_file($file_info['group_name'], $file_info['filename'], $local_filename)下载文件
FastDFS PHP functions:

string fastdfs_client_version()
return client library version


long fastdfs_get_last_error_no()
return last error no


string fastdfs_get_last_error_info()
return last error info


string fastdfs_http_gen_token(string file_id, int timestamp)
generate anti-steal token for HTTP download
parameters:
	file_id: the file id (including group name and filename)
	timestamp: the timestamp (unix timestamp)
return token string for success, false for error


array fastdfs_get_file_info(string group_name, string filename)
get file info from the filename
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
return assoc array for success, false for error.
	the assoc array including following elements:
		create_timestamp: the file create timestamp (unix timestamp)
		file_size: the file size (bytes)
		source_ip_addr: the source storage server ip address


array fastdfs_get_file_info1(string file_id)
get file info from the file id
parameters:
	file_id: the file id (including group name and filename) or remote filename
return assoc array for success, false for error.
	the assoc array including following elements:
		create_timestamp: the file create timestamp (unix timestamp)
		file_size: the file size (bytes)
		source_ip_addr: the source storage server ip address


bool fastdfs_send_data(int sock, string buff)
parameters:
	sock: the unix socket description
	buff: the buff to send
return true for success, false for error


string fastdfs_gen_slave_filename(string master_filename, string prefix_name
                [, string file_ext_name])
generate slave filename by master filename, prefix name and file extension name
parameters:
	master_filename: the master filename / file id to generate
			the slave filename
	prefix_name: the prefix name  to generate the slave filename
	file_ext_name: slave file extension name, can be null or emtpy
			(do not including dot)
return slave filename string for success, false for error


boolean fastdfs_storage_file_exist(string group_name, string remote_filename
	[, array tracker_server, array storage_server])
check file exist
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for exist, false for not exist


boolean fastdfs_storage_file_exist1(string file_id
	[, array tracker_server, array storage_server])
parameters:
	file_id: the file id of the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for exist, false for not exist


array fastdfs_storage_upload_by_filename(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_by_filename1(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error.


array fastdfs_storage_upload_by_filebuff(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_by_filebuff1(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array fastdfs_storage_upload_by_callback(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


array fastdfs_storage_upload_by_callback1(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array fastdfs_storage_upload_appender_by_filename(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server as appender file
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_appender_by_filename1(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server as appender file
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error.


array fastdfs_storage_upload_appender_by_filebuff(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server as appender file
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_appender_by_filebuff1(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server as appender file
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array fastdfs_storage_upload_appender_by_callback(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback as appender file
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_appender_by_callback1(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback as appender file
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


boolean fastdfs_storage_append_by_filename(string local_filename,
	string group_name, appender_filename
	[, array tracker_server, array storage_server])
append local file to the appender file of storage server
parameters:
	local_filename: the local filename
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



string fastdfs_storage_append_by_filename1(string local_filename,
	string appender_file_id [, array tracker_server, array storage_server])
append local file to the appender file of storage server
parameters:
	local_filename: the local filename
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_append_by_filebuff(string file_buff,
	string group_name, string appender_filename
	[, array tracker_server, array storage_server])
append file buff to the appender file of storage server
parameters:
	file_buff: the file content
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_append_by_filebuff1(string file_buff,
	string appender_file_id [, array tracker_server, array storage_server])
append file buff to the appender file of storage server
parameters:
	file_buff: the file content
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_append_by_callback(array callback_array,
	string group_name, string appender_filename
	[, array tracker_server, array storage_server])
append file to the appender file of storage server by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_append_by_callback1(array callback_array,
	string appender_file_id [, array tracker_server, array storage_server])
append file buff to the appender file of storage server
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_modify_by_filename(string local_filename,
	long file_offset, string group_name, appender_filename,
	[array tracker_server, array storage_server])
modify appender file by local file
parameters:
	local_filename: the local filename
        file_offset: offset of appender file
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_modify_by_filename1(string local_filename,
	long file_offset, string appender_file_id
        [, array tracker_server, array storage_server])
modify appender file by local file
parameters:
	local_filename: the local filename
        file_offset: offset of appender file
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_modify_by_filebuff(string file_buff,
	long file_offset, string group_name, string appender_filename
	[, array tracker_server, array storage_server])
modify appender file by file buff
parameters:
	file_buff: the file content
        file_offset: offset of appender file
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_modify_by_filebuff1(string file_buff,
	long file_offset, string appender_file_id
	[, array tracker_server, array storage_server])
modify appender file by file buff
parameters:
	file_buff: the file content
        file_offset: offset of appender file
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_modify_by_callback(array callback_array,
	long file_offset, string group_name, string appender_filename
	[, array tracker_server, array storage_server])
modify appender file by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
        file_offset: offset of appender file
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_modify_by_callback1(array callback_array,
	long file_offset, string group_name, string appender_filename
	[, array tracker_server, array storage_server])
modify appender file by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
        file_offset: offset of appender file
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_truncate_file(string group_name,
	string appender_filename [, long truncated_file_size = 0,
	array tracker_server, array storage_server])
truncate appender file to specify size
parameters:
	group_name: the the group name of appender file
	appender_filename: the appender filename
        truncated_file_size: truncate the file size to
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean fastdfs_storage_truncate_file1(string appender_file_id
	[, long truncated_file_size = 0, array tracker_server,
	array storage_server])
truncate appender file to specify size
parameters:
	appender_file_id: the appender file id
        truncated_file_size: truncate the file size to
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


string/array fastdfs_storage_upload_slave_by_filename(string local_filename,
	string group_name, string master_filename, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload local file to storage server (slave file mode)
parameters:
	file_buff: the file content
	group_name: the group name of the master file
	master_filename: the master filename to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_slave_by_filename1(string local_filename,
	string master_file_id, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload local file to storage server (slave file mode)
parameters:
	local_filename: the local filename
	master_file_id: the master file id to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error.


array fastdfs_storage_upload_slave_by_filebuff(string file_buff,
	string group_name, string master_filename, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file buff to storage server (slave file mode)
parameters:
	file_buff: the file content
	group_name: the group name of the master file
	master_filename: the master filename to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_slave_by_filebuff1(string file_buff,
	string master_file_id, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file buff to storage server (slave file mode)
parameters:
	file_buff: the file content
	master_file_id: the master file id to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array fastdfs_storage_upload_slave_by_callback(array callback_array,
	string group_name, string master_filename, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file to storage server by callback (slave file mode)
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	group_name: the group name of the master file
	master_filename: the master filename to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string fastdfs_storage_upload_slave_by_callback1(array callback_array,
	string master_file_id, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file to storage server by callback (slave file mode)
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	master_file_id: the master file id to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


boolean fastdfs_storage_delete_file(string group_name, string remote_filename
	[, array tracker_server, array storage_server])
delete file from storage server
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean fastdfs_storage_delete_file1(string file_id
	[, array tracker_server, array storage_server])
delete file from storage server
parameters:
	file_id: the file id to be deleted
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


string fastdfs_storage_download_file_to_buff(string group_name,
	string remote_filename [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
get file content from storage server
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return the file content for success, false for error


string fastdfs_storage_download_file_to_buff1(string file_id
        [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
get file content from storage server
parameters:
	file_id: the file id of the file
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return the file content for success, false for error


boolean fastdfs_storage_download_file_to_file(string group_name,
	string remote_filename, string local_filename [, long file_offset,
	long download_bytes, array tracker_server, array storage_server])
download file from storage server to local file
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	local_filename: the local filename to save the file content
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean fastdfs_storage_download_file_to_file1(string file_id,
	string local_filename [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
download file from storage server to local file
parameters:
	file_id: the file id of the file
	local_filename: the local filename to save the file content
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean fastdfs_storage_download_file_to_callback(string group_name,
	string remote_filename, array download_callback [, long file_offset,
	long download_bytes, array tracker_server, array storage_server])
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	download_callback: the download callback array, elements as:
			callback  - the php callback function name
                                    callback function prototype as:
				    function my_download_file_callback($args, $file_size, $data)
			args      - use argument for callback function
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean fastdfs_storage_download_file_to_callback1(string file_id,
	array download_callback [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
parameters:
	file_id: the file id of the file
	download_callback: the download callback array, elements as:
			callback  - the php callback function name
                                    callback function prototype as:
				    function my_download_file_callback($args, $file_size, $data)
			args      - use argument for callback function
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean fastdfs_storage_set_metadata(string group_name, string remote_filename,
	array meta_list [, string op_type, array tracker_server,
	array storage_server])
set meta data of the file
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	meta_list: meta data assoc array to be set, such as
                   array('width'=>1024, 'height'=>768)
	op_type: operate flag, can be one of following flags:
		FDFS_STORAGE_SET_METADATA_FLAG_MERGE: combined with the old meta data
		FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE: overwrite the old meta data
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean fastdfs_storage_set_metadata1(string file_id, array meta_list
	[, string op_type, array tracker_server, array storage_server])
set meta data of the file
parameters:
	file_id: the file id of the file
	meta_list: meta data assoc array to be set, such as
                   array('width'=>1024, 'height'=>768)
	op_type: operate flag, can be one of following flags:
		FDFS_STORAGE_SET_METADATA_FLAG_MERGE: combined with the old meta data
		FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE: overwrite the old meta data
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


array fastdfs_storage_get_metadata(string group_name, string remote_filename
	[, array tracker_server, array storage_server])
get meta data of the file
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       returned array like: array('width' => 1024, 'height' => 768)


array fastdfs_storage_get_metadata1(string file_id
	[, array tracker_server, array storage_server])
get meta data of the file
parameters:
	file_id: the file id of the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       returned array like: array('width' => 1024, 'height' => 768)


array fastdfs_connect_server(string ip_addr, int port)
connect to the server
parameters:
	ip_addr: the ip address of the server
	port: the port of the server
return assoc array for success, false for error


boolean fastdfs_disconnect_server(array server_info)
disconnect from the server
parameters:
	server_info: the assoc array including elements:
                     ip_addr, port and sock
return true for success, false for error


boolean fastdfs_active_test(array server_info)
send ACTIVE_TEST cmd to the server
parameters:
	server_info: the assoc array including elements:
                     ip_addr, port and sock, sock must be connected
return true for success, false for error


array fastdfs_tracker_get_connection()
get a connected tracker server
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


boolean fastdfs_tracker_make_all_connections()
connect to all tracker servers
return true for success, false for error


boolean fastdfs_tracker_close_all_connections()
connect all connections to the tracker servers
return true for success, false for error


array fastdfs_tracker_list_groups([string group_name, array tracker_server])
get group stat info
parameters:
	group_name: specify the group name, null or empty string means all groups
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return index array for success, false for error, each group as a array element


array fastdfs_tracker_query_storage_store([string group_name,
		array tracker_server])
get the storage server info to upload file
parameters:
	group_name: specify the group name
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error. the assoc array including
       elements: ip_addr, port, sock and store_path_index


array fastdfs_tracker_query_storage_store_list([string group_name,
		array tracker_server])
get the storage server list to upload file
parameters:
	group_name: specify the group name
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return indexed storage server array for success, false for error.
       each element is an ssoc array including elements:
       ip_addr, port, sock and store_path_index


array fastdfs_tracker_query_storage_update(string group_name,
		string remote_filename [, array tracker_server])
get the storage server info to set metadata
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


array fastdfs_tracker_query_storage_update1(string file_id,
		[, array tracker_server])
get the storage server info to set metadata
parameters:
	file_id: the file id of the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


array fastdfs_tracker_query_storage_fetch(string group_name,
		string remote_filename [, array tracker_server])
get the storage server info to download file (or get metadata)
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock

array fastdfs_tracker_query_storage_fetch1(string file_id
		[, array tracker_server])
get the storage server info to download file (or get metadata)
parameters:
	file_id: the file id of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


array fastdfs_tracker_query_storage_list(string group_name,
		string remote_filename [, array tracker_server])
get the storage server list which can retrieve the file content or metadata
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return index array for success, false for error.
       each server as an array element


array fastdfs_tracker_query_storage_list1(string file_id
		[, array tracker_server])
get the storage server list which can retrieve the file content or metadata
parameters:
	file_id: the file id of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return index array for success, false for error.
       each server as an array element


boolean fastdfs_tracker_delete_storage(string group_name, string storage_ip)
delete the storage server from the cluster
parameters:
	group_name: the group name of the storage server
	storage_ip: the ip address of the storage server to be deleted
return true for success, false for error


FastDFS Class Info:

class FastDFS([int config_index, boolean bMultiThread]);
FastDFS class constructor
params:
        config_index: use which config file, base 0. default is 0
        bMultiThread: if in multi-thread, default is false


long FastDFS::get_last_error_no()
return last error no


string FastDFS::get_last_error_info()
return last error info

bool FastDFS::send_data(int sock, string buff)
parameters:
	sock: the unix socket description
	buff: the buff to send
return true for success, false for error


string FastDFS::http_gen_token(string file_id, int timestamp)
generate anti-steal token for HTTP download
parameters:
	file_id: the file id (including group name and filename)
	timestamp: the timestamp (unix timestamp)
return token string for success, false for error


array FastDFS::get_file_info(string group_name, string filename)
get file info from the filename
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
return assoc array for success, false for error.
	the assoc array including following elements:
		create_timestamp: the file create timestamp (unix timestamp)
		file_size: the file size (bytes)
		source_ip_addr: the source storage server ip address
		crc32: the crc32 signature of the file


array FastDFS::get_file_info1(string file_id)
get file info from the file id
parameters:
	file_id: the file id (including group name and filename) or remote filename
return assoc array for success, false for error.
	the assoc array including following elements:
		create_timestamp: the file create timestamp (unix timestamp)
		file_size: the file size (bytes)
		source_ip_addr: the source storage server ip address


string FastDFS::gen_slave_filename(string master_filename, string prefix_name
                [, string file_ext_name])
generate slave filename by master filename, prefix name and file extension name
parameters:
	master_filename: the master filename / file id to generate
			the slave filename
	prefix_name: the prefix name  to generate the slave filename
	file_ext_name: slave file extension name, can be null or emtpy
			(do not including dot)
return slave filename string for success, false for error


boolean FastDFS::storage_file_exist(string group_name, string remote_filename
	[, array tracker_server, array storage_server])
check file exist
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for exist, false for not exist


boolean FastDFS::storage_file_exist1(string file_id
	[, array tracker_server, array storage_server])
parameters:
	file_id: the file id of the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for exist, false for not exist


array FastDFS::storage_upload_by_filename(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_by_filename1(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error.


array FastDFS::storage_upload_by_filebuff(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_by_filebuff1(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array FastDFS::storage_upload_by_callback(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


array FastDFS::storage_upload_by_callback1(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array FastDFS::storage_upload_appender_by_filename(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server as appender file
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_appender_by_filename1(string local_filename
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload local file to storage server as appender file
parameters:
	local_filename: the local filename
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error.


array FastDFS::storage_upload_appender_by_filebuff(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server as appender file
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_appender_by_filebuff1(string file_buff
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file buff to storage server as appender file
parameters:
	file_buff: the file content
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array FastDFS::storage_upload_appender_by_callback(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback as appender file
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_appender_by_callback1(array callback_array
	[, string file_ext_name, array meta_list, string group_name,
	array tracker_server, array storage_server])
upload file to storage server by callback as appender file
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	group_name: specify the group name to store the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


boolean FastDFS::storage_append_by_filename(string local_filename,
	string group_name, appender_filename
	[, array tracker_server, array storage_server])
append local file to the appender file of storage server
parameters:
	local_filename: the local filename
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



string FastDFS::storage_upload_by_filename1(string local_filename,
	[string appender_file_id, array tracker_server, array storage_server])
append local file to the appender file of storage server
parameters:
	local_filename: the local filename
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_append_by_filebuff(string file_buff,
	string group_name, string appender_filename
	[, array tracker_server, array storage_server])
append file buff to the appender file of storage server
parameters:
	file_buff: the file content
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_append_by_filebuff1(string file_buff,
	string appender_file_id [, array tracker_server, array storage_server])
append file buff to the appender file of storage server
parameters:
	file_buff: the file content
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_append_by_callback(array callback_array,
	string group_name, string appender_filename
	[, array tracker_server, array storage_server])
append file to the appender file of storage server by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_append_by_callback1(array callback_array,
	string appender_file_id [, array tracker_server, array storage_server])
append file buff to the appender file of storage server
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_modify_by_filename(string local_filename,
	long file_offset, string group_name, appender_filename,
	[array tracker_server, array storage_server])
modify appender file by local file
parameters:
	local_filename: the local filename
        file_offset: offset of appender file
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_modify_by_filename1(string local_filename,
	long file_offset, string appender_file_id
        [, array tracker_server, array storage_server])
modify appender file by local file
parameters:
	local_filename: the local filename
        file_offset: offset of appender file
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_modify_by_filebuff(string file_buff,
	long file_offset, string group_name, string appender_filename
	[, array tracker_server, array storage_server])
modify appender file by file buff
parameters:
	file_buff: the file content
        file_offset: offset of appender file
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_modify_by_filebuff1(string file_buff,
	long file_offset, string appender_file_id
	[, array tracker_server, array storage_server])
modify appender file by file buff
parameters:
	file_buff: the file content
        file_offset: offset of appender file
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_modify_by_callback(array callback_array,
	long file_offset, string group_name, string appender_filename
	[, array tracker_server, array storage_server])
modify appender file by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
        file_offset: offset of appender file
	group_name: the the group name of appender file
	appender_filename: the appender filename
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_modify_by_callback1(array callback_array,
	long file_offset, string group_name, string appender_filename
	[, array tracker_server, array storage_server])
modify appender file by callback
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
        file_offset: offset of appender file
	appender_file_id: the appender file id
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_truncate_file(string group_name,
	string appender_filename [, long truncated_file_size = 0,
	array tracker_server, array storage_server])
truncate appender file to specify size
parameters:
	group_name: the the group name of appender file
	appender_filename: the appender filename
        truncated_file_size: truncate the file size to
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



boolean FastDFS::storage_truncate_file1(string appender_file_id
	[, long truncated_file_size = 0, array tracker_server,
	array storage_server])
truncate appender file to specify size
parameters:
	appender_file_id: the appender file id
        truncated_file_size: truncate the file size to
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error



array FastDFS::storage_upload_slave_by_filename(string local_filename,
	string group_name, string master_filename, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload local file to storage server (slave file mode)
parameters:
	file_buff: the file content
	group_name: the group name of the master file
	master_filename: the master filename to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_slave_by_filename1(string local_filename,
	string master_file_id, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload local file to storage server (slave file mode)
parameters:
	local_filename: the local filename
	master_file_id: the master file id to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error.


array FastDFS::storage_upload_slave_by_filebuff(string file_buff,
	string group_name, string master_filename, string prefix_name
	[, file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file buff to storage server (slave file mode)
parameters:
	file_buff: the file content
	group_name: the group name of the master file
	master_filename: the master filename to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_slave_by_filebuff1(string file_buff,
	string master_file_id, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file buff to storage server (slave file mode)
parameters:
	file_buff: the file content
	master_file_id: the master file id to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


array FastDFS::storage_upload_slave_by_callback(array callback_array,
	string group_name, string master_filename, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file to storage server by callback (slave file mode)
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	group_name: the group name of the master file
	master_filename: the master filename to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error.
       the returned array includes elements: group_name and filename


string FastDFS::storage_upload_slave_by_callback1(array callback_array,
	string master_file_id, string prefix_name
	[, string file_ext_name, array meta_list,
	array tracker_server, array storage_server])
upload file to storage server by callback (slave file mode)
parameters:
	callback_array: the callback assoc array, must have keys:
			callback  - the php callback function name
                                    callback function prototype as:
				    function upload_file_callback($sock, $args)
			file_size - the file size
			args      - use argument for callback function
	master_file_id: the master file id to generate the slave file id
	prefix_name: the prefix name to generage the slave file id
	file_ext_name: the file extension name, do not include dot(.)
	meta_list: meta data assoc array, such as
                   array('width'=>1024, 'height'=>768)
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return file_id for success, false for error


boolean FastDFS::storage_delete_file(string group_name, string remote_filename
	[, array tracker_server, array storage_server])
delete file from storage server
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean FastDFS::storage_delete_file1(string file_id
	[, array tracker_server, array storage_server])
delete file from storage server
parameters:
	file_id: the file id to be deleted
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


string FastDFS::storage_download_file_to_buff(string group_name,
	string remote_filename [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
get file content from storage server
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return the file content for success, false for error


string FastDFS::storage_download_file_to_buff1(string file_id
        [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
get file content from storage server
parameters:
	file_id: the file id of the file
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return the file content for success, false for error


boolean FastDFS::storage_download_file_to_file(string group_name,
	string remote_filename, string local_filename [, long file_offset,
	long download_bytes, array tracker_server, array storage_server])
download file from storage server to local file
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	local_filename: the local filename to save the file content
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean FastDFS::storage_download_file_to_file1(string file_id,
	string local_filename [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
download file from storage server to local file
parameters:
	file_id: the file id of the file
	local_filename: the local filename to save the file content
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean FastDFS::storage_download_file_to_callback(string group_name,
	string remote_filename, array download_callback [, long file_offset,
	long download_bytes, array tracker_server, array storage_server])
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	download_callback: the download callback array, elements as:
			callback  - the php callback function name
                                    callback function prototype as:
				    function my_download_file_callback($args, $file_size, $data)
			args      - use argument for callback function
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean FastDFS::storage_download_file_to_callback1(string file_id,
	array download_callback [, long file_offset, long download_bytes,
	array tracker_server, array storage_server])
parameters:
	file_id: the file id of the file
	download_callback: the download callback array, elements as:
			callback  - the php callback function name
                                    callback function prototype as:
				    function my_download_file_callback($args, $file_size, $data)
			args      - use argument for callback function
	file_offset: file start offset, default value is 0
	download_bytes: 0 (default value) means from the file offset to
                        the file end
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean FastDFS::storage_set_metadata(string group_name, string remote_filename,
	array meta_list [, string op_type, array tracker_server,
	array storage_server])
set meta data of the file
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	meta_list: meta data assoc array to be set, such as
                   array('width'=>1024, 'height'=>768)
	op_type: operate flag, can be one of following flags:
		FDFS_STORAGE_SET_METADATA_FLAG_MERGE: combined with the old meta data
		FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE: overwrite the old meta data
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


boolean FastDFS::storage_set_metadata1(string file_id, array meta_list
	[, string op_type, array tracker_server, array storage_server])
set meta data of the file
parameters:
	file_id: the file id of the file
	meta_list: meta data assoc array to be set, such as
                   array('width'=>1024, 'height'=>768)
	op_type: operate flag, can be one of following flags:
		FDFS_STORAGE_SET_METADATA_FLAG_MERGE: combined with the old meta data
		FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE: overwrite the old meta data
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return true for success, false for error


array FastDFS::storage_get_metadata(string group_name, string remote_filename
	[, array tracker_server, array storage_server])
get meta data of the file
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       returned array like: array('width' => 1024, 'height' => 768)


array FastDFS::storage_get_metadata1(string file_id
	[, array tracker_server, array storage_server])
get meta data of the file
parameters:
	file_id: the file id of the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
	storage_server: the storage server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       returned array like: array('width' => 1024, 'height' => 768)


array FastDFS::connect_server(string ip_addr, int port)
connect to the server
parameters:
	ip_addr: the ip address of the server
	port: the port of the server
return assoc array for success, false for error


boolean FastDFS::disconnect_server(array server_info)
disconnect from the server
parameters:
	server_info: the assoc array including elements:
                     ip_addr, port and sock
return true for success, false for error


array FastDFS::tracker_get_connection()
get a connected tracker server
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


boolean FastDFS::active_test(array server_info)
send ACTIVE_TEST cmd to the server
parameters:
	server_info: the assoc array including elements:
                     ip_addr, port and sock, sock must be connected
return true for success, false for error


boolean FastDFS::tracker_make_all_connections()
connect to all tracker servers
return true for success, false for error


boolean FastDFS::tracker_close_all_connections()
connect all connections to the tracker servers
return true for success, false for error


array FastDFS::tracker_list_groups([string group_name, array tracker_server])
get group stat info
parameters:
	group_name: specify the group name, null or empty string means all groups
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return index array for success, false for error, each group as a array element


array FastDFS::tracker_query_storage_store([string group_name,
		array tracker_server])
get the storage server info to upload file
parameters:
	group_name: specify the group name
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error. the assoc array including
       elements: ip_addr, port, sock and store_path_index


array FastDFS::tracker_query_storage_store_list([string group_name,
		array tracker_server])
get the storage server list to upload file
parameters:
	group_name: specify the group name
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return indexed storage server array for success, false for error.
       each element is an ssoc array including elements:
       ip_addr, port, sock and store_path_index


array FastDFS::tracker_query_storage_update(string group_name,
		string remote_filename [, array tracker_server])
get the storage server info to set metadata
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


array FastDFS::tracker_query_storage_update1(string file_id,
		[, array tracker_server])
get the storage server info to set metadata
parameters:
	file_id: the file id of the file
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


array FastDFS::tracker_query_storage_fetch(string group_name,
		string remote_filename [, array tracker_server])
get the storage server info to download file (or get metadata)
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock

array FastDFS::tracker_query_storage_fetch1(string file_id
		[, array tracker_server])
get the storage server info to download file (or get metadata)
parameters:
	file_id: the file id of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return assoc array for success, false for error
       the assoc array including elements: ip_addr, port and sock


array FastDFS::tracker_query_storage_list(string group_name,
		string remote_filename [, array tracker_server])
get the storage server list which can retrieve the file content or metadata
parameters:
	group_name: the group name of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return index array for success, false for error.
       each server as an array element


array FastDFS::tracker_query_storage_list1(string file_id
		[, array tracker_server])
get the storage server list which can retrieve the file content or metadata
parameters:
	file_id: the file id of the file
	remote_filename: the filename on the storage server
	tracker_server: the tracker server assoc array including elements:
                        ip_addr, port and sock
return index array for success, false for error.
       each server as an array element


boolean  FastDFS::tracker_delete_storage(string group_name, string storage_ip)
delete the storage server from the cluster
parameters:
	group_name: the group name of the storage server
	storage_ip: the ip address of the storage server to be deleted
return true for success, false for error

void FastDFS::close()
close tracker connections
 *
 *
 *
 *
 *
 *
$group_name = "group1";
 $remote_filename = "M00/28/E3/U6Q-CkrMFUgAAAAAAAAIEBucRWc5452.h";
 $file_id = $group_name . FDFS_FILE_ID_SEPERATOR . $remote_filename;

 echo fastdfs_client_version() . "\n";

 //$file_id = $group_name . FDFS_FILE_ID_SEPERATOR . 'M00/00/02/wKjRbExc_qIAAAAAAABtNw6hsnM56585.part2.c';

 //var_dump(fastdfs_get_file_info1($file_id));
 //exit(1);


 echo 'fastdfs_tracker_make_all_connections result: ' . fastdfs_tracker_make_all_connections() . "\n";
 var_dump(fastdfs_tracker_list_groups());

 $tracker = fastdfs_tracker_get_connection();
 var_dump($tracker);

 if (!fastdfs_active_test($tracker))
 {
        error_log("errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info());
        exit(1);
 }

 $server = fastdfs_connect_server($tracker['ip_addr'], $tracker['port']);
 var_dump($server);
 var_dump(fastdfs_disconnect_server($server));
 var_dump($server);

 var_dump(fastdfs_tracker_query_storage_store_list());

 $storage = fastdfs_tracker_query_storage_store();
 if (!$storage)
 {
        error_log("errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info());
        exit(1);
 }

 $server = fastdfs_connect_server($storage['ip_addr'], $storage['port']);
 if (!$server)
 {
        error_log("errno1: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info());
        exit(1);
 }
 if (!fastdfs_active_test($server))
 {
        error_log("errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info());
        exit(1);
 }

 //var_dump(fastdfs_tracker_list_groups($tracker));

 $storage['sock'] = $server['sock'];
 $file_info = fastdfs_storage_upload_by_filename("/usr/include/stdio.h", null, array(), null, $tracker, $storage);
 if ($file_info)
 {
        $group_name = $file_info['group_name'];
        $remote_filename = $file_info['filename'];

        var_dump($file_info);
        var_dump(fastdfs_get_file_info($group_name, $remote_filename));
        echo "file exist: " . fastdfs_storage_file_exist($group_name, $remote_filename) . "\n";

        $master_filename = $remote_filename;
        $prefix_name = '.part1';
        $slave_file_info = fastdfs_storage_upload_slave_by_filename("/usr/include/stdio.h",
                $group_name, $master_filename, $prefix_name);
        if ($slave_file_info !== false)
        {
        var_dump($slave_file_info);

        $generated_filename = fastdfs_gen_slave_filename($master_filename, $prefix_name);
        if ($slave_file_info['filename'] != $generated_filename)
        {
                echo "${slave_file_info['filename']}\n != \n${generated_filename}\n";
        }

        echo "delete slave file return: " . fastdfs_storage_delete_file($slave_file_info['group_name'], $slave_file_info['filename']) . "\n";
        }
        else
        {
                echo "fastdfs_storage_upload_slave_by_filename fail, errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info() . "\n";
        }

        echo "delete file return: " . fastdfs_storage_delete_file($file_info['group_name'], $file_info['filename']) . "\n";
 }

 $file_id = fastdfs_storage_upload_by_filename1("/usr/include/stdio.h", null, array('width'=>1024, 'height'=>800, 'font'=>'Aris', 'Homepage' => true, 'price' => 103.75, 'status' => FDFS_STORAGE_STATUS_ACTIVE), '', $tracker, $storage);
 if ($file_id)
 {
        $master_file_id = $file_id;
        $prefix_name = '.part2';
        $slave_file_id = fastdfs_storage_upload_slave_by_filename1("/usr/include/stdio.h",
                $master_file_id, $prefix_name);
        if ($slave_file_id !== false)
        {
        var_dump($slave_file_id);

        $generated_file_id = fastdfs_gen_slave_filename($master_file_id, $prefix_name);
        if ($slave_file_id != $generated_file_id)
        {
                echo "${slave_file_id}\n != \n${generated_file_id}\n";
        }

        echo "delete file $slave_file_id return: " . fastdfs_storage_delete_file1($slave_file_id) . "\n";
        }
        else
        {
                echo "fastdfs_storage_upload_slave_by_filename1 fail, errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info() . "\n";
        }

        echo "delete file $file_id return: " . fastdfs_storage_delete_file1($file_id) . "\n";
 }

 $file_info = fastdfs_storage_upload_by_filebuff("this is a test.", "txt");
 if ($file_info)
 {
        $group_name = $file_info['group_name'];
        $remote_filename = $file_info['filename'];

        var_dump($file_info);
        var_dump(fastdfs_get_file_info($group_name, $remote_filename));
        echo "file exist: " . fastdfs_storage_file_exist($group_name, $remote_filename) . "\n";

        $ts = time();
        $token = fastdfs_http_gen_token($group_name . FDFS_FILE_ID_SEPERATOR . $remote_filename, $ts);
        echo "token=$token\n";

        $file_content = fastdfs_storage_download_file_to_buff($file_info['group_name'], $file_info['filename']);
        echo "file content: " . $file_content . "(" . strlen($file_content) . ")\n";
        $local_filename = 't1.txt';
        echo 'storage_download_file_to_file result: ' .
                fastdfs_storage_download_file_to_file($file_info['group_name'], $file_info['filename'], $local_filename) . "\n";

        echo "fastdfs_storage_set_metadata result: " . fastdfs_storage_set_metadata(
                $file_info['group_name'], $file_info['filename'],
                array('color'=>'', 'size'=>32, 'font'=>'MS Serif'), FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE) . "\n";

        $meta_list = fastdfs_storage_get_metadata($file_info['group_name'], $file_info['filename']);
        var_dump($meta_list);

        $master_filename = $remote_filename;
        $prefix_name = '.part1';
        $file_ext_name = 'txt';
        $slave_file_info = fastdfs_storage_upload_slave_by_filebuff('this is slave file.',
                $group_name, $master_filename, $prefix_name, $file_ext_name);
        if ($slave_file_info !== false)
        {
        var_dump($slave_file_info);

        $generated_filename = fastdfs_gen_slave_filename($master_filename, $prefix_name, $file_ext_name);
        if ($slave_file_info['filename'] != $generated_filename)
        {
                echo "${slave_file_info['filename']}\n != \n${generated_filename}\n";
        }

        echo "delete slave file return: " . fastdfs_storage_delete_file($slave_file_info['group_name'], $slave_file_info['filename']) . "\n";
        }
        else
        {
                echo "fastdfs_storage_upload_slave_by_filebuff fail, errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info() . "\n";
        }

        echo "delete file return: " . fastdfs_storage_delete_file($file_info['group_name'], $file_info['filename']) . "\n";
 }

 $file_id = fastdfs_storage_upload_by_filebuff1("this\000is\000a\000test.", "bin",
                array('width'=>1024, 'height'=>768, 'font'=>'Aris'));
 if ($file_id)
 {
        $file_content = fastdfs_storage_download_file_to_buff1($file_id);
        echo "file content: " . $file_content . "(" . strlen($file_content) . ")\n";
        $local_filename = 't2.txt';
        echo 'storage_download_file_to_file1 result: ' .
                fastdfs_storage_download_file_to_file1($file_id, $local_filename) . "\n";
        echo "fastdfs_storage_set_metadata1 result: " . fastdfs_storage_set_metadata1(
                $file_id, array('color'=>'yellow', 'size'=>'1234567890', 'font'=>'MS Serif'),
                FDFS_STORAGE_SET_METADATA_FLAG_MERGE) . "\n";
        $meta_list = fastdfs_storage_get_metadata1($file_id);
        var_dump($meta_list);

        $master_file_id = $file_id;
        $prefix_name = '.part2';
        $file_ext_name = 'txt';
        $slave_file_id = fastdfs_storage_upload_slave_by_filebuff1('this is slave file1.',
                $master_file_id, $prefix_name, $file_ext_name);
        if ($slave_file_id !== false)
        {
        var_dump($slave_file_id);

        $generated_file_id = fastdfs_gen_slave_filename($master_file_id, $prefix_name, $file_ext_name);
        if ($slave_file_id != $generated_file_id)
        {
                echo "${slave_file_id}\n != \n${generated_file_id}\n";
        }

        echo "delete file $slave_file_id return: " . fastdfs_storage_delete_file1($slave_file_id) . "\n";
        }
        else
        {
                echo "fastdfs_storage_upload_slave_by_filebuff1 fail, errno: " . fastdfs_get_last_error_no() . ", error info: " . fastdfs_get_last_error_info() . "\n";
        }

        echo "delete file $file_id return: " . fastdfs_storage_delete_file1($file_id) . "\n";
 }

 var_dump(fastdfs_tracker_query_storage_update($group_name, $remote_filename));
 var_dump(fastdfs_tracker_query_storage_fetch($group_name, $remote_filename));
 var_dump(fastdfs_tracker_query_storage_list($group_name, $remote_filename));
 var_dump(fastdfs_tracker_query_storage_update1($file_id));
 var_dump(fastdfs_tracker_query_storage_fetch1($file_id, $tracker));
 var_dump(fastdfs_tracker_query_storage_list1($file_id, $tracker));

 echo "fastdfs_tracker_close_all_connections result: " . fastdfs_tracker_close_all_connections() . "\n";

 $fdfs = new FastDFS();
 echo 'tracker_make_all_connections result: ' . $fdfs->tracker_make_all_connections() . "\n";
 $tracker = $fdfs->tracker_get_connection();
 var_dump($tracker);

 $server = $fdfs->connect_server($tracker['ip_addr'], $tracker['port']);
 var_dump($server);
 var_dump($fdfs->disconnect_server($server));

 var_dump($fdfs->tracker_query_storage_store_list());

 //var_dump($fdfs->tracker_list_groups());
 //var_dump($fdfs->tracker_query_storage_store());
 //var_dump($fdfs->tracker_query_storage_update($group_name, $remote_filename));
 //var_dump($fdfs->tracker_query_storage_fetch($group_name, $remote_filename));
 //var_dump($fdfs->tracker_query_storage_list($group_name, $remote_filename));

 var_dump($fdfs->tracker_query_storage_update1($file_id));
 var_dump($fdfs->tracker_query_storage_fetch1($file_id));
 var_dump($fdfs->tracker_query_storage_list1($file_id));

 $file_info = $fdfs->storage_upload_by_filename("/usr/include/stdio.h");
 if ($file_info)
 {
        $group_name = $file_info['group_name'];
        $remote_filename = $file_info['filename'];

        var_dump($file_info);
        var_dump($fdfs->get_file_info($group_name, $remote_filename));
        echo "file exist: " . $fdfs->storage_file_exist($group_name, $remote_filename) . "\n";

        $master_filename = $remote_filename;
        $prefix_name = '.part1';
        $slave_file_info = $fdfs->storage_upload_slave_by_filename("/usr/include/stdio.h",
                $group_name, $master_filename, $prefix_name);
        if ($slave_file_info !== false)
        {
        var_dump($slave_file_info);

        $generated_filename = $fdfs->gen_slave_filename($master_filename, $prefix_name);
        if ($slave_file_info['filename'] != $generated_filename)
        {
                echo "${slave_file_info['filename']}\n != \n${generated_filename}\n";
        }

        echo "delete slave file return: " . $fdfs->storage_delete_file($slave_file_info['group_name'], $slave_file_info['filename']) . "\n";
        }
        else
        {
                echo "storage_upload_slave_by_filename fail, errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info() . "\n";
        }

        echo "delete file return: " . $fdfs->storage_delete_file($file_info['group_name'], $file_info['filename']) . "\n";
 }

 $file_ext_name = 'c';
 $file_id = $fdfs->storage_upload_by_filename1("/usr/include/stdio.h", $file_ext_name, array('width'=>1024, 'height'=>800, 'font'=>'Aris'));
 if ($file_id)
 {
        $master_file_id = $file_id;
        $prefix_name = '.part2';
        $slave_file_id = $fdfs->storage_upload_slave_by_filename1("/usr/include/stdio.h",
                $master_file_id, $prefix_name, $file_ext_name);
        if ($slave_file_id !== false)
        {
        var_dump($slave_file_id);

        $generated_file_id = $fdfs->gen_slave_filename($master_file_id, $prefix_name, $file_ext_name);
        if ($slave_file_id != $generated_file_id)
        {
                echo "${slave_file_id}\n != \n${generated_file_id}\n";
        }

        echo "delete file $slave_file_id return: " . $fdfs->storage_delete_file1($slave_file_id) . "\n";
        }
        else
        {
                echo "fastdfs_storage_upload_slave_by_filename1 fail, errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info() . "\n";
        }

        echo "delete file $file_id return: " . $fdfs->storage_delete_file1($file_id) . "\n";
 }

 $file_info = $fdfs->storage_upload_by_filebuff("", "txt");
 if ($file_info)
 {
        var_dump($file_info);
        $file_content = $fdfs->storage_download_file_to_buff($file_info['group_name'], $file_info['filename']);
        echo "file content: " . $file_content . "(" . strlen($file_content) . ")\n";
        $local_filename = 't3.txt';
        echo 'storage_download_file_to_file result: ' .
                $fdfs->storage_download_file_to_file($file_info['group_name'], $file_info['filename'], $local_filename) . "\n";

        echo "storage_set_metadata result: " . $fdfs->storage_set_metadata(
                $file_info['group_name'], $file_info['filename'],
                array('color'=>'yellow', 'size'=>32), FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE) . "\n";

        $meta_list = $fdfs->storage_get_metadata($file_info['group_name'], $file_info['filename']);
        var_dump($meta_list);

        $master_filename = $file_info['filename'];
        $prefix_name = '.part1';
        $file_ext_name = 'txt';
        $slave_file_info = $fdfs->storage_upload_slave_by_filebuff('this is slave file  1 by class.',
                $file_info['group_name'], $master_filename, $prefix_name, $file_ext_name);
        if ($slave_file_info !== false)
        {
        var_dump($slave_file_info);

        $generated_filename = $fdfs->gen_slave_filename($master_filename, $prefix_name, $file_ext_name);
        if ($slave_file_info['filename'] != $generated_filename)
        {
                echo "${slave_file_info['filename']}\n != \n${generated_filename}\n";
        }

        echo "delete slave file return: " . $fdfs->storage_delete_file($slave_file_info['group_name'], $slave_file_info['filename']) . "\n";
        }
        else
        {
                echo "storage_upload_slave_by_filebuff fail, errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info() . "\n";
        }

        echo "delete file return: " . $fdfs->storage_delete_file($file_info['group_name'], $file_info['filename']) . "\n";
 }

 $file_id = $fdfs->storage_upload_by_filebuff1("this\000is\001a\002test.", "bin",
                array('color'=>'none', 'size'=>0, 'font'=>'Aris'));
 if ($file_id)
 {
        var_dump($fdfs->get_file_info1($file_id));
        echo "file exist: " . $fdfs->storage_file_exist1($file_id) . "\n";

        $ts = time();
        $token = $fdfs->http_gen_token($file_id, $ts);
        echo "token=$token\n";

        $file_content = $fdfs->storage_download_file_to_buff1($file_id);
        echo "file content: " . $file_content . "(" . strlen($file_content) . ")\n";
        $local_filename = 't4.txt';
        echo 'storage_download_file_to_file1 result: ' . $fdfs->storage_download_file_to_file1($file_id, $local_filename) . "\n";
        echo "storage_set_metadata1 result: " . $fdfs->storage_set_metadata1(
                $file_id, array('color'=>'yellow', 'size'=>32), FDFS_STORAGE_SET_METADATA_FLAG_MERGE) . "\n";

        $master_file_id = $file_id;
        $prefix_name = '.part2';
        $file_ext_name = 'txt';
        $slave_file_id = $fdfs->storage_upload_slave_by_filebuff1('this is slave file 2 by class.',
                $master_file_id, $prefix_name, $file_ext_name);
        if ($slave_file_id !== false)
        {
        var_dump($slave_file_id);

        $generated_file_id = $fdfs->gen_slave_filename($master_file_id, $prefix_name, $file_ext_name);
        if ($slave_file_id != $generated_file_id)
        {
                echo "${slave_file_id}\n != \n${generated_file_id}\n";
        }

        echo "delete file $slave_file_id return: " . $fdfs->storage_delete_file1($slave_file_id) . "\n";
        }
        else
        {
                echo "storage_upload_slave_by_filebuff1 fail, errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info() . "\n";
        }

        $meta_list = $fdfs->storage_get_metadata1($file_id);
        if ($meta_list !== false)
        {
                var_dump($meta_list);
        }
        else
        {
                echo "errno: " . $fdfs->get_last_error_no() . ", error info: " . $fdfs->get_last_error_info() . "\n";
        }

        echo "delete file $file_id return: " . $fdfs->storage_delete_file1($file_id) . "\n";
 }

 var_dump($fdfs->active_test($tracker));
 echo 'tracker_close_all_connections result: ' . $fdfs->tracker_close_all_connections() . "\n";

*/