<?php
/**
 * 
 * Enter description here ...
 * @author		hugb <hu198021688500@163.com>
 * @copyright	2011-2012
 * @version	1.0
 * @package	
 *
 *	$Id$
 */
class MyFastDFS{
	
	private $__errorInfo;
	
	public function __construct(){
		fastdfs_tracker_make_all_connections();
	}
	
	public function __destruct(){
		fastdfs_tracker_close_all_connections();
	}
	
	/**
	 * 上传本地文件到FastDFS集群
	 * Enter description here ...
	 * @param string $fileName the local filename
	 * @param string $fileExtName optional the file extension name, do not include dot(.)
	 * @param array $metaList optional meta data assoc array, such as array('width'=>1024, 'height'=>768)
	 * @param string $groupName optional specify the group name to store the file
	 * @return array return assoc array for success, false for error. the returned array includes elements: group_name and filename
	 */
	public function upload($fileName, $fileExtName = null, $metaList = array(), $groupName = null){
		if (!is_file($fileName)){
			return false;
		}else{
			$tracker = $this->_getTrackerCon();
			$storage = $this->_getStorageCon();
			return fastdfs_storage_upload_by_filename($fileName, $fileExtName, $metaList, $groupName, $tracker, $storage);
		}
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
	public function buffToUpload($buff, $fileExtName = null, $metaList = array(), $groupName = null){
		$tracker = $this->_getTrackerCon();
		$storage = $this->_getStorageCon();
		return fastdfs_storage_upload_by_filebuff($buff, $fileExtName, $metaList, $groupName, $tracker, $storage);
	}
	
	/**
	 * 
	 * Enter description here ...
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
	public function uploadCallback($callback, $fileExtName = null, $metaList = array(), $groupName = null){
		$tracker = $this->_getTrackerCon();
		$storage = $this->_getStorageCon();
		return fastdfs_storage_upload_by_callback($callback, $fileExtName, $metaList, $groupName, $tracker, $storage);
	}
	
	/**
	 * 从一个包含文件信息的数组中得到文件Id
	 * Enter description here ...
	 * @param array $fileInfo
	 * @return string
	 */
	public function getFileId($fileInfo){
		return $fileInfo['group_name'] . FDFS_FILE_ID_SEPERATOR . $fileInfo['filename'];
	}
	
	/**
	 * 获取远程文件的信息
	 * Enter description here ...
	 * @param string $fileId the file id of the file
	 * @return array
	 */
	public function getFileInfo($fileId){
		return fastdfs_get_file_info1($fileId);
	}
	
	/**
	 * 下载文件到本地
	 * Enter description here ...
	 * @param string $fileId the file id of the file
	 * @param string $localFilename the local filename to save the file content
	 * @return boolean return true for success, false for error
	 */
	public function download($fileId, $localFilename){
		//$tracker = $this->_getTrackerCon();
		//$storage = $this->_getStorageCon();
		return fastdfs_storage_download_file_to_file1($fileId, $localFilename);
	}
	
	/**
	 * 下载文件内容到缓冲区
	 * Enter description here ...
	 * @param string $fileId the file id of the file
	 * @return boolean return the file content for success, false for error
	 */
	public function downloadToBuff($fileId){
		return fastdfs_storage_download_file_to_buff($fileId);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param string $fileId
	 * @param array $callback the sameto upload
	 * @return boolean return true for success, false for error
	 */
	public function downloadCallback($fileId, $callback){
		return fastdfs_storage_download_file_to_callback1($fileId, $callback);
	}
	
	/**
	 * 删除文件
	 * Enter description here ...
	 * @param string $fileId
	 * @return boolean
	 */
	public function delete($fileId){
		return fastdfs_storage_delete_file1($fileId);
	}
	
	/**
	 * 获取错误信息
	 * Enter description here ...
	 */
	public function getError(){
		return $this->__errorInfo;
	}
	
	/**
	 * 设置错误信息
	 * Enter description here ...
	 */
	private function setError(){
		$this->__errorInfo = fastdfs_get_last_error_no() . " " . fastdfs_get_last_error_info();
		exit(1);
	}
	
	/**
	 * 获取tracker服务器连接
	 * Enter description here ...
	 * @param string $ip
	 * @param int $port
	 * @return array
	 */
	private function _getTrackerCon($ip = null, $port = null){
		if (is_null($ip) || is_null($port)){
			$tracker = fastdfs_tracker_get_connection();
		}else{
			$tracker = fastdfs_connect_server($ip, $port); 
		}
		return $tracker;
	}
	
	/**
	 * 获取storage服务器连接
	 * Enter description here ...
	 * @param string $ip
	 * @param int $port
	 * @return array
	 */
	private function _getStorageCon($ip = null, $port = null){
		if (is_null($ip) || is_null($port)){
			$storage = fastdfs_tracker_query_storage_store();
		}else{
			$storage = fastdfs_connect_server($ip, $port);
		}
		if (!$storage){
			$this->setError();
		}
		return $storage;
	}
	
	/**
	 * 释放服务器连接
	 * Enter description here ...
	 * @param array $server
	 */
	private function _disCon($server){
		return fastdfs_disconnect_server($server);
	}
}
/*
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
*/