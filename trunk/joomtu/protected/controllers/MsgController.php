<?php

/**
 * 站内信
 * 2012-4-5 14:31:23 UTF-8
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

/**
 * 站内信
 *
 * @author hugb
 */
class MsgController extends CController {

    public function actionIndex() {
        $this->render('index');
    }

    public function actionInbox() {
        
    }

    public function actionOutbox() {
        
    }

    public function actionWrite() {
        
    }

    public function actionAjaxContact() {
        var_dump(Yii::app()->request->baseUrl);
        echo 1;
    }

}

?>
