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

class UserRegisterForm extends CFormModel{
	
	public $email;
	public $password;
	public $confirmPwd;
	public $name;
	public $verifyCode;
	public $agree;

	/**
	 * 表单验证规则
	 * @see CModel::rules()
	 * @return array
	 */
	public function rules(){
		return array(
			array('email,password,confirmPwd,name', 'required'),
			array('email', 'email'),
			array('email', 'length', 'min'=>5, 'max'=>32),
			array('email', 'unique', 'className'=>'User', 'attributeName'=>'email'),
			array('password,confirmPwd', 'length', 'min'=>6, 'max'=>32),
			array('confirmPwd', 'compare', 'compareAttribute'=>'password', 'operator'=>'=', 'strict'=>true),
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements() || !Yii::app()->params['registerCaptcha']),
			array('agree', 'checkAgree')
		);
	}
	
	/**
	 * 检查是否选中已阅读服务协议
	 * @param string $attribute
	 * @param array $params
	 */
	public function checkAgree($attribute, $params){
		if (!$this->agree){
			$this->addError('agree', 'Please read the licence.');
		}
	}
	
	/**
	 * 属性标签
	 * @see CModel::attributeLabels()
	 * @return array
	 */
	public function attributeLabels(){
		return array(
			'email'=> Yii::t('models', 'User Email'),
			'password'=> Yii::t('models', 'Password'),
			'confirmPwd'=> Yii::t('models', 'Confirm Password'),
			'verifyCode'=>Yii::t('models', 'Verification Code'),
			'agree'=>Yii::t('application', 'Read the <a target="_blank" href="/user/licence">licence</a>.')
		);
	}
	
	/**
	 * 保存注册用户的基本信息并向neo4j中添加该用户
	 * @return boolean
	 */
	public function saveUser(){
		$userRel = new UserRel();
		$neo4jId = $userRel->createNode(array('email'=>$this->email, 'name'=>$this->name));
		if (!$neo4jId) {
			return false;
		}
		$user = new User();
		$user->uid = $neo4jId;
		$user->email = $this->email;
		$user->salt = $user->generateSalt();
		$user->password = $user->hashPassword($this->password, $user->salt);
		$user->name = $this->name;
		$user->rid = 2;	// 角色为普通用户
		$user->nid = $neo4jId;
		$user->reg_time = $user->update_time = time();
		$user->reg_ip = Yii::app()->getRequest()->getUserHostAddress();
		$flagOne = $user->save();
		if ($user->uid) {
			$flagTwo = $userRel->setNodeAttr($neo4jId, 'uid', (int)$user->uid);
		} else {
			return false;
		}
		return true;
	}
}