<?php
/**
 * ShopController class
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @link http://www.jooomtu.com/
 * @copyright Copyright &copy; 2011-2015 Joomtu Software LLC
 * @license http://www.jooomtu.com/license/
 */


/**
 * UserController
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @version $Id$
 * @package application.controllers
 * @since 1.0
 */

class ShopController extends SBaseController{
	
	public $layout='//layouts/column1';
	
	public function actions(){
		return array(
			'edit' => 'application.controllers.post.UpdateAction',
			'action2' => array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	
	public function actionIndex(){
		$this->render('index');
	}
}