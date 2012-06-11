<?php
/**
 * 前台控制器基类
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2011-2015 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


/**
 * FBController must be extended by all of the applications controllers
 * if the auto srbac should be used.
 * You can import it in your main config file as<br />
 * 'import'=>array(<br />
 * 'application.modules.srbac.controllers.FBController',<br />
 * ),
 *
 * @author Hu Guobing <hu198021688500@163.com>
 * @version $Id$
 * @package srbac.controllers
 * @since 1.0
 */
Yii::import("srbac.components.Helper");

class FrontController extends CController{
	
	public $menu=array();
	public $breadcrumbs=array();
	public $layout='//layouts/column1';

	public function init(){
		parent::init();
		$cs = Yii::app()->clientScript;
		$cs->scriptMap = array(
			'jquery.js'=>Yii::app()->params['siteRes'].'script/jquery-1.6.4.js',
		);
	}
	
	protected function beforeAction($action) {
		$del = Helper::findModule('srbac')->delimeter;
		$mod = $this->module !== null ? $this->module->id . $del : "";
		$contrArr = explode("/", $this->id);
		$contrArr[sizeof($contrArr) - 1] = ucfirst($contrArr[sizeof($contrArr) - 1]);
		$controller = implode(".", $contrArr);
		$controller = str_replace("/", ".", $this->id);
		if(sizeof($contrArr)==1){
			$controller = ucfirst($controller);
		}
		$access = $mod . $controller . ucfirst($this->action->id);
		if (in_array($access, $this->allowedAccess())) {
			return true;
		}
		if (!Yii::app()->getModule('srbac')->isInstalled()) {
			return true;
		}
		if (Yii::app()->getModule('srbac')->debug) {
			return true;
		}
		if (!Yii::app()->user->checkAccess($access) || Yii::app()->user->isGuest) {
			$this->onUnauthorizedAccess();
		} else {
			return true;
		}
	}
	
	protected function allowedAccess() {
		Yii::import("srbac.components.Helper");
		return Helper::findModule('srbac')->getAlwaysAllowed();
	}

	protected function onUnauthorizedAccess() {
		if (Yii::app()->user->isGuest) {
			Yii::app()->user->loginRequired();
		} else {
			$mod = $this->module !== null ? $this->module->id : "";
			$access = $mod . ucfirst($this->id) . ucfirst($this->action->id);
			$error["code"] = "403";
			$error["title"] = Helper::translate('srbac', 'You are not authorized for this action');
			$error["message"] = Helper::translate('srbac', 'Error while trying to access') . ' ' . $mod . "/" . $this->id . "/" . $this->action->id . ".";
			//You may change the view for unauthorized access
			if (Yii::app()->request->isAjaxRequest) {
				$this->renderPartial(Yii::app()->getModule('srbac')->notAuthorizedView, array("error" => $error));
			} else {
				$this->render(Yii::app()->getModule('srbac')->notAuthorizedView, array("error" => $error));
			}
			return false;
		}
	}
}