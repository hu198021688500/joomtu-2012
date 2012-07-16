<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity{
	
	private $_id;
	
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate(){
		$user = User::model()->find('LOWER(username)=?', array(strtolower($this->username)));
		if($user === null){
			//throw new CHttpException(404,'The requested page does not exist.');
			//throw new CException('The is an example of throwing a CException');
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		}else if(!$user->validatePassword($this->password)){
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		}else{
			$this->_id = $user->id;
			$this->username = $user->username;
			$this->errorCode = self::ERROR_NONE;
			$this->setState('roles', 'merchant');
			//Yii::app()->user->roles;
		}
		return $this->errorCode == self::ERROR_NONE;
	}
	
	/**
	 * @return integer the ID of the user record
	 */
	public function getId(){
		return $this->_id;
	}
}