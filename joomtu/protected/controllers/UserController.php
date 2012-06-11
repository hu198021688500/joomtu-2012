<?php

/**
 * 2012-3-7 01:22:50
 * @package protected.controllers
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */
class UserController extends Controller {

    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'transparent' => true
            )
        );
    }

    public function actionIndex() {
        $uid = Yii::app()->getRequest()->getParam('id');
        if (empty($uid)) {
            $this->redirect('/user/home');
        } else {
            $viewData = array();
            $user = new User();
            $viewData['user'] = $user->getUserByUid($uid);
            if (empty($viewData['user'])) {
                $this->redirect('/site/index');
            }
            if (!Yii::app()->user->isGuest) {
                $rel = new UserRel();
                $viewData['toMeRels'] = $rel->getUserRelsNames($uid, Yii::app()->user->id);
            }
            $this->render('index', $viewData);
        }
    }

    public function actionHome() {
        if (Yii::app()->user->isGuest) {
            $this->redirect('/site/index');
        }
    }

    public function actionProfile() {
        if (Yii::app()->user->isGuest) {
            $this->redirect('/site/index');
        }
        $this->refresh();
    }

    public function actionRegister() {
        $model = new UserRegisterForm();
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-register-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['UserRegisterForm'])) {
            $model->attributes = $_POST['UserRegisterForm'];
            if ($model->validate() && $model->saveUser()) {
                $this->redirect(Yii::app()->homeUrl);
            }
        }
        // display the register form
        $this->render('register', array('model' => $model));
    }

    public function actionLogin() {
        $model = new UserLoginForm;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['UserLoginForm'])) {
            $model->attributes = $_POST['UserLoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login()) {
                Yii::log("Successful login of user: " . Yii::app()->user->id, "info", "application.controllers.SiteController");
                $this->redirect(Yii::app()->user->returnUrl);
            } else {
                Yii::log("Failed login attempt", "warning", "application.controllers.SiteController");
            }
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionLicence() {
        $this->render('licence');
    }

}