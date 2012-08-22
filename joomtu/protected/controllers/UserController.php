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

    public function init() {
        parent::init();
        //Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/styles/user.css');
    }

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

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-register-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['UserRegisterForm'])) {
            $model->attributes = $_POST['UserRegisterForm'];
            if ($model->validate() && $model->saveUser()) {
                $this->redirect(Yii::app()->homeUrl);
            }
        }

        $this->render('register', array(
            'model' => $model
        ));
    }

    /**
     * user login
     */
    public function actionLogin() {
        $model = new UserLoginForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['UserLoginForm'])) {
            $model->attributes = $_POST['UserLoginForm'];
            if ($model->validate() && $model->login()) {
                Yii::log("Successful login of user: " . Yii::app()->user->id, "info", "application.controllers.SiteController");
                $this->redirect(Yii::app()->user->returnUrl);
            } else {
                Yii::log("Failed login attempt", "warning", "application.controllers.SiteController");
            }
        }

        $this->render('login', array(
            'model' => $model
        ));
    }

    /**
     * find password
     */
    public function actionFindPass() {
        $model = new UserFindPassForm();

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'user-find-pass-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['UserFindPassForm'])) {
            $model->attributes = $_POST['UserFindPassForm'];
            if ($model->validate()) {
                if ($model->sendMailUse163()) {
                    Yii::app()->user->setFlash('send_email_status', true);
                } else {
                    Yii::app()->user->setFlash('send_email_status', false);
                }
                Yii::app()->user->setFlash('send_email_address', $model->email);
                $this->refresh();
            }
        }

        $this->render('findpass', array(
            'model' => $model
        ));
    }

    public function actionResetPwd() {

    }

    /**
     * user logout
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * service licence
     */
    public function actionLicence() {
        $this->render('licence');
    }

    public function actionAvatar() {
        $this->layout = false;
        if (Yii::app()->getRequest()->isPostRequest) {
            $file = CUploadedFile::getInstanceByName("file");
            if ($file == false) {
                return false;
            }
            $file->saveAs('/var/www/xx.jpg');
        }

        $js = Yii::app()->request->baseUrl . '/scripts/ImageCropper.js';
        Yii::app()->clientScript->registerScriptFile($js);
        $this->render('avatar');
    }

}