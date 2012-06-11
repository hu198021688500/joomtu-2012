<?php

/**
 * 2011-12-29 11:31:06
 * @package package_name
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */
class WebUser extends CWebUser {

    protected function beforeLogin($id, $states, $fromCookie) {
        return true;
    }

}