<?php
/**
 * SiteController class
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2011-2015 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * UserController
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @version $Id$
 * @package application.controllers
 * @since 1.0
 */

class SiteController extends FrontController{
	
	public function actions(){
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
				'minLength'=>4,
				'maxLength'=>4,
				'transparent'=>true,
				'testLimit'=>4//default 3
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}
	
	public function actionIndex(){
		//$db1Rows = Yii::app()->db->createCommand($sql)->queryAll();
		//$db2Rows = Yii::app()->db_slave->createCommand('select * from mb_user where user_id = 1885')->queryAll();
		
		//$db2Rows = Topic::model()->findByPk(28);
		//$topic = new Topic('db_slave');
		//$topic->selectDb('db_slave');
		//$db2Rows = $topic->findByPk(28);
		//print_r($db2Rows);
		//echo Yii::app()->getController()->getId();
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){
				Yii::log("Successful login of user: " . Yii::app()->user->id, "info", "application.controllers.SiteController");
				$this->redirect(Yii::app()->user->returnUrl);
			}else{
				Yii::log("Failed login attempt", "warning", "application.controllers.SiteController");
			}
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
	public function actionShowLog()
	{
		echo "Logged Messages:<br><br>";
		var_dump(Yii::getLogger()->getLogs());
	}
}