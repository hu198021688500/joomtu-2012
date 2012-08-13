<?php

/**
 * 2011-10-27 16:09:02
 * @package protected.components
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 *
 */
class CommonComponent {

    public function setWidth($value) {
        $this->_width = $value;
    }

    public function getWidth() {
        return $this->_width;
    }

    public function onClicked($event) {
        $this->raiseEvent('onClicked', $event);
    }

    /* 过滤验证方法集合 by joffe 围脖@狂code诗人 */
    /* 转义可能引起sql注入的特殊字符.
      $string 需要转移的字符串;
      $forece 是否强制转换
      #string 转后的字符串.
     */

//$magic_quotes_gpc = get_magic_quotes_gpc();
    function tdsql($string, $force = 0) {
        if (!$GLOBALS['magic_quotes_gpc'] || $force) {
            if (is_array($string)) {
                foreach ($string as $key => $val) {
                    $string[$key] = tdsql($val, $force);
                }
            } else {
                $string = addslashes($string);
            }
        }
        return $string;
    }

    /* 验证是否email
      string 需要验证的字符串
      # boolen 符合email格式返回true,不符合返回false
     */

    function is_email($string) {
        if (preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 验证是否url
      string 需要验证的字符串是否url
      # 是返回true
     */

    function is_url($string) {
        if (preg_match("/^[\w-]+://(\w+(-\w+)*)(\.(\w+(-\w+)*))*(\?\S*)?$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    function is_num($str) {
        return is_numeric($str);
    }

    function is_chinese($string) {
        if (preg_match("/[^\x00-\xff]/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    function is_qq($string) {
        if (preg_match('/^(\d{5,10})$/', $string)) {
            return true;
        } else {
            return false;
        };
    }

    /* 安全字符串,就是只含有英文数字和_ */

    function is_safe_str($string) {
        if (preg_match("/^\w+$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 用户名串只有中英文和数字 6到21字节 */

    function is_username($string) {
        if (preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z0-9]{6,21}$/u", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 密码字符串可以含有部分特殊字符的的 4-18位 */

    function is_passwd($string) {
        if (preg_match("/^[\@A-Za-z0-9\!\#\$\%\^\&\*\.\~]{6,22}$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 中国身份证 */

    function is_shenfenzheng($string) {
        if (preg_match("/^(\d{18}|\d{15}|\d{17}x)$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 手机号码 11位简单判断 */

    function is_phone($string) {
        if (preg_match("/^\d{11}$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 移动手机号码 */

    function is_phone_yd($string) {
        if (preg_match("/^[134-139,147,150-152,157-159,181-183,187-188]\d{9}$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 联通手机号码 */

    function is_phone_lt($string) {
        if (preg_match("/^[130-132,155,156,185,186]\d{9}$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 电信手机号码 */

    function is_phone_dx($string) {
        if (preg_match("/^[133,153,180,189]\d{9}$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 固定电话 */

    function is_telphone($string) {
        if (preg_match("/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{8}$/", $string)) {
            return true;
        } else {
            return false;
        }
    }

    /* 验证URL 是否来自本站 */

    function xxx() {
        if (isset($_GET['url'])) {
            if (!is_myurl($_GET['url'])) {
                $_GET['url'] = "http://" . $_SERVER['HTTP_HOST'] . "/";
            }
        }
    }

    function is_myurl($string) {
        $arr_url = parse_url($string);
        if ($arr_url['host'] == $_SERVER['HTTP_HOST']) {
            return true;
        } else {
            return false;
        }
    }

    /* 用户名可以含有中文 */

    function is_username_cn($string) {
        if (preg_match("/^(?!_|\s\')[A-Za-z0-9_\x80-\xff\s\']{6,27}$/", $string)) {
            return true;
        } else {
            return false;
        };
    }

}