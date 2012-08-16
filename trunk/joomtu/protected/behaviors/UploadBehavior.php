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

        function my_download_file_callback($args, $file_size, $data) {
            var_dump($args);

            if ($args['fhandle'] == NULL) {
                $args['fhandle'] = fopen($args['filename'], 'w');
                if (!$args['fhandle']) {
                    echo 'open file: ' . $args['filename'] . " fail!\n";
                    return false;
                }
            }

            $len = strlen($data);
            if (fwrite($args['fhandle'], $data, $len) === false) {
                echo 'write to file: ' . $args['filename'] . " fail!\n";
                $result = false;
            } else {
                $args['write_bytes'] += $len;
                $result = true;
            }

            if ((!$result) || $args['write_bytes'] >= $file_size) {
                fclose($args['fhandle']);
                $args['fhandle'] = NULL;
                $args['write_bytes'] = 0;
            }

            return $result;
        }

        $download_callback_arg = array(
            'filename' => '/tmp/out.txt',
            'write_bytes' => 0,
            'fhandle' => NULL
        );
        $download_callback_array = array(
            'callback' => 'my_download_file_callback',
            'args' => &$download_callback_arg);

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
