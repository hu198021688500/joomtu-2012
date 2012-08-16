<?php

/**
 * 2012-8-16 11:33:09 UTF-8
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
 * Description of UploadBehavior
 */
class UploadBehavior {

    private $__commonGroupName;
    private $__avatarGroupName;
    private $__goodsGroupName;

    public function __construct() {
        $this->__commonGroupName = 'common';
        $this->__avatarGroupName = 'avatar';
        $this->__goodsGroupName = 'goods';
    }

    public function upload($filename) {
        fastdfs_storage_upload_by_filename($filename, null, null, $this->__commonGroupName);
    }

    public function uploadAvatar($filename) {

    }

    public function test() {
        //var_dump(fastdfs_client_version());
        //var_dump(fastdfs_gen_slave_filename('M01/02/76/wKgUy0-grau35AoQAADAuWPGnjc502', '_main'));
        //var_dump(fastdfs_gen_slave_filename('M01/02/76/wKgUy0-grau35AoQAADAuWPGnjc502.png', '_main', 'jpg'));
        //var_dump(fastdfs_storage_file_exist1('group2/M00/00/00/wKgjvFAspQvwa5mDAAAGkCLo-iI4365.sh'));
        //var_dump(fastdfs_get_file_info1('group2/M00/00/00/wKgjvFAspQvwa5mDAAAGkCLo-iI4365.sh'));
        //var_dump(fastdfs_http_gen_token('M00/00/00/wKgjvFAspQvwa5mDAAAGkCLo-iI4365.sh', time()));
        //var_dump(fastdfs_storage_set_metadata1('group2/M00/00/00/wKgjvFAspQvwa5mDAAAGkCLo-iI4365.sh', array('user_id' => 1009)));
        //var_dump(fastdfs_storage_get_metadata1('group2/M00/00/00/wKgjvFAspQvwa5mDAAAGkCLo-iI4365.sh'));
        //fastdfs_storage_download_file_to_callback1('group2/M00/00/00/wKgjvFAspQvwa5mDAAAGkCLo-iI4365.sh', $download_callback_array);


        die();
    }

}

?>
