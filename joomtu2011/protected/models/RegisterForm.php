<?php
/**
 * RegisterForm class
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @link http://www.jooomtu.com/
 * @copyright Copyright &copy; 2011-2015 Joomtu Software LLC
 * @license http://www.jooomtu.com/license/
 */


/**
 * RegisterForm is the data structure for keeping
 * Register form data. It is used by the 'register' action of 'UserController'.
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @version $Id$
 * @package application.models
 * @since 1.0
 */

class RegisterForm extends CFormModel{
	
	public $username;
	public $password;
	public $confirmPwd;
	
	public $email;
	public $verifyCode;

	
	public function rules(){
		return array(
			array('username, password, confirmPwd', 'required'),
			array('confirmPwd','compare', 'compareAttribute'=>'password', 'operator'=>'=', 'strict'=>true),
			array('email', 'email'),
			array('verifyCode', 'captcha', 'on'=>'insert', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}
	
	public function attributeLabels(){
		return array(
			'username'=> Yii::t('models', 'User Name'),
			'password'=> Yii::t('models', 'Password'),
			'confirmPwd'=> Yii::t('models', 'Confirm Password'),
			'email'=> Yii::t('models', 'Email'),
			'verifyCode'=>Yii::t('models', 'Verification Code'),
		);
	}
}