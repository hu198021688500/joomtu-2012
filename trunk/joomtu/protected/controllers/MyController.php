<?php

/**
 * 2012-8-13 14:47:05 UTF-8
 * @package
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011-2015
 * @license ()
 *
 * $Id$
 *
 */

/**
 * 用户中心
 */
class MyController extends Controller {

    public function actionIndex() {
        if (Yii::app()->user->getIsGuest()) {
            Yii::app()->user->loginRequired();
        }

        $this->render('index');
    }

}