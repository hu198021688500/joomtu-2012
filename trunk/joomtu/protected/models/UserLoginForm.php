<?php
/**
 * LoginForm class
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @link http://www.jooomtu.com/
 * @copyright Copyright &copy; 2011-2015 Joomtu Software LLC
 * @license http://www.jooomtu.com/license/
 */


/**
 * LoginForm is the data structure for keeping
 * Register form data. It is used by the 'register' action of 'UserController'.
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @version $Id$
 * @package application.models
 * @since 1.0
 */

class UserLoginForm extends CFormModel{
	
	public $username;
	public $password;
	public $verifyCode;
	public $rememberMe;
	
	private $_identity;
	
	/**
	 * 验证规则
	 * @see CModel::rules()
	 */
	public function rules(){
		return array(
			array('username,password', 'required'),
			array('password', 'authenticate'),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements() || !Yii::app()->params['loginCaptcha']),
			array('rememberMe', 'boolean')
		);
	}
	
	/**
	 * 属性标签
	 * @see CModel::attributeLabels()
	 */
	public function attributeLabels(){
		return array(
			'rememberMe'=>'Remember me next time( a month)',
		);
	}

	/**
	 * 验证密码
	 */
	public function authenticate($attribute,$params){
		if(!$this->hasErrors()){
			$this->_identity = new UserIdentity($this->username, $this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

	/**
	 * 用户登录
	 * @return boolean whether login is successful
	 */
	public function login(){
		if($this->_identity === null){
			$this->_identity = new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode === UserIdentity::ERROR_NONE){
			$duration = $this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);
			return true;
		}else{
			return false;
		}
	}
}
