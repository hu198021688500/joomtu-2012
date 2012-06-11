<?php
/**
 * 默认后台控制器类
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2011-2015 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @version $Id$
 * @package admin.controllers
 * @since 1.0
 */
class DefaultController extends BackController{
	
	public function init(){
		parent::init();
	}
	
	public function actions(){
		return array(
			'treeMoveUp'=>'ext.tree.actions.TreeMoveUpAction',
			'treeMoveDown'=>'ext.tree.actions.TreeMoveDownAction',
			'treeCreate'=>'ext.tree.actions.TreeCreateAction',
			'treeUpdate'=>'ext.tree.actions.TreeUpdateAction',
			'treeLoad'=>'ext.tree.actions.TreeLoadAction',
			'treeDelete'=>'ext.tree.actions.TreeDeleteAction',
			'treeChildren'=>'ext.tree.actions.TreeChildrenAction',
			'treeChildrenParent'=>'ext.tree.actions.TreeChildrenParentAction',
		);
	}
    
	public function actionIndex(){
		//echo $param = Yii::app()->controller->module->param;
		// or the following if $this refers to the controller instance
		// $postPerPage=$this->module->postPerPage;
		$this->renderPartial('index');
	}
	
	public function actionLogin(){
		$this->renderPartial('login');
	}
	
	public function actionHeader(){
		$this->render('header');
	}
	
	public function actionMenu(){
		$this->render('menu');
	}
	
	public function actionMain(){
		$this->render('main');
	}
	
	
	public function actionTest(){
		$this->layout = "admin";
		$this->render('test');
	}
}