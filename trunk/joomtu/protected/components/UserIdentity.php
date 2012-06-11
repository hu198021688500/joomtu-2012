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
class UserIdentity extends CUserIdentity {

    private $uid;
    private $email;

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $user = User::model()->find('LOWER(email)=?', array(strtolower($this->username)));
        if ($user === null) {
            //throw new CHttpException(404,'The requested page does not exist.');
            //throw new CException('The is an example of throwing a CException');
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if (!$user->validatePassword($this->password)) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->uid = $user->uid;
            $this->username = $user->name;
            $this->email = $user->email;
            $this->setState('nickname', $user->name);
            $this->setState('role', $user->rid);
            //Yii::app()->user->roles;
            $this->errorCode = self::ERROR_NONE;
        }
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * @return integer the ID of the user record
     */
    public function getId() {
        return $this->uid;
    }

    public function getEmail() {
        return $this->email;
    }

}