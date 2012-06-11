<?php
/**
 * UploadController class
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @link http://www.jooomtu.com/
 * @copyright Copyright &copy; 2011-2015 Joomtu Software LLC
 * @license http://www.jooomtu.com/license/
 */


/**
 * UploadController
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @version $Id$
 * @package application.controllers
 * @since 1.0
 */

class UploadController extends Controller{
	
	public function actionIndex(){
		$this->render('index');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}