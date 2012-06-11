<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel{
	
	public $username;
	public $password;
	public $rememberMe;
	//public $logo;
	
	private $_identity;
	
	public function rules(){
		/**
		 * boolean: Alias of CBooleanValidator, 只有true和 false
		 * captcha: Alias of CCaptchaValidator, 验证码验证
		 * compare: Alias of CCompareValidator, 对比表单里面的属性或者常量
		 * email: Alias of CEmailValidator, 确认是个正确的e-mail 地址
		 * default: Alias of CDefaultVAlidator, 按照默认值设置
		 * exist: Alias of CExistValidator, 确定可以从表的字段里面找到in the specified table column
		 * file: Alias of CFileValidator, 确定属性包括上传文件名an uploaded file，简单说就是确认有上传文件
		 * filter: Alias of CFilterValidator, transforming the attribute with a filter
		 * in: Alias of CRangeValidator, 在指定的列表里面存在
		 * length: Alias of CStringValidator, 大小限制
		 * match: Alias of CRegularExpressionValidator, 确定数据匹配一个正则表达式
		 * numerical: Alias of CNumberValidator, 确定是个有效数字
		 * required: Alias of CRequiredValidator, 确定不能为空
		 * type: Alias of CTypeValidator,确定属性是个特定的类型
		 * unique: Alias of CUniqueValidator, 确定字段在表里的列是唯一字段
		 * url: Alias of CUrlValidator, 确定是个正确的url
		 */
		return array(
			/*array('create_user_id, update_user_id', 'numerical','integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, description, create_time, create_user_id,update_time, update_user_id', 'safe', 'on'=>'search'),
			array('logo', 'file','allowEmpty'=>true,'types'=>'jpg, gif, png','maxSize'=>1024 * 1024 * 1,'tooLarge'=>'图片太大，请选择小于1MB的图片。'),
			*/
			/*$image = CUploadedFile::getInstance($model, 'logo');
			if (is_object($image) && get_class($image) === 'CUploadedFile'){
				$model->logo = 'D:/aaa/aaa.jpg';  //请根据自己的需求生成相应的路径，但是要记得和下面保存路径保持一致
			}else {
				$model->logo = 'NoPic.jpg';
			}
			if($model->save()){
	 			if (is_object($image) && get_class($image)==='CUploadedFile'){
					$image->saveAs('D:/aaa/aa.jpg');//路径必须真实存在，并且如果是linux系统，必须有修改权限
	 			}
	 			$this->redirect(array(‘view’,'id’=>$model->BookId));
			}
			*/

			array('username, password', 'required'),
			array('rememberMe', 'boolean'),
			array('password', 'authenticate'),
		);
	}
	
	public function attributeLabels(){
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity = new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration = $this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity, $duration);
			return true;
		}
		else
			return false;
	}
}
