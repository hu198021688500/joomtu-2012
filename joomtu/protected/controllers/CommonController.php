<?php

/**
 * 2012-3-7 03:18:19
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
class CommonController extends Controller {

    public function actionMessage() {
        $this->render('message');
    }

}