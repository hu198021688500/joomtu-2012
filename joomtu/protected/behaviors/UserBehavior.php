<?php

/**
 * 2012-4-5 15:21:14 UTF-8
 * @package protected.behaviors
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 *
 */

/**
 * Description of UserBehavior
 *
 * @author hadoop
 */
class UserBehavior {

    public function getLoginUserId() {
        return 1;
    }

    public function isExistence($uid) {
        return $uid > 0;
    }

    public function getUserDetail($uid) {
        return User::model()->findByPk($uid);
    }

}

?>
