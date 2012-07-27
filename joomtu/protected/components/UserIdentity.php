<?php

/**
 * 用户身份认证
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
class UserIdentity extends /* CUserIdentity */CBaseUserIdentity {

    public $uid;
    public $email;
    public $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function authenticate() {
        $user = User::model()->find('email = ?', array($this->email));
        if ($user === null) {
            //throw new CHttpException(404,'The requested page does not exist.');
            //throw new CException('The is an example of throwing a CException');
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->uid = $user->uid;
            $this->email = $user->email;
            $this->setState('nickname', $user->nickname);
            $this->setState('role', $user->rid);
            //Yii::app()->user->roles;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId() {
        return $this->uid;
    }

    public function getEmail() {
        return $this->email;
    }

}