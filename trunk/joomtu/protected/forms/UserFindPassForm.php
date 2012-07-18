<?php

/**
 * 2012-7-18 17:39:19 UTF-8
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
 * Description of UserFindPassForm
 */
class UserFindPassForm extends CFormModel {

    public $username;
    public $verifyCode;

    /**
     * 验证规则
     * @see CModel::rules()
     */
    public function rules() {
        return array(
            array('username', 'required'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements())
        );
    }

    /**
     * 属性标签
     * @see CModel::attributeLabels()
     */
    public function attributeLabels() {
        return array(
            'username' => 'E-mail',
            'verifyCode' => 'verifyCode'
        );
    }

    public function sendMail() {
        return true;
    }

}

?>
