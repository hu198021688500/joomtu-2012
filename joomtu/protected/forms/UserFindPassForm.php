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

    public $email;
    public $verifyCode;

    /**
     * 验证规则
     * @see CModel::rules()
     */
    public function rules() {
        return array(
            array('email', 'required'),
            array('email', 'email'),
            array('email', 'emailIsExistence'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements())
        );
    }

    public function attributeLabels() {
        return array(
            'email' => 'E-mail',
            'verifyCode' => 'verifyCode'
        );
    }

    public function emailIsExistence($attribute, $params) {
        $user = User::model()->find('email = ?', array($this->$attribute));
        if (empty($user)) {
            $this->addError($attribute, $this->email . 'does not exist');
        }
    }

    public function sendMailUse163() {
        Yii::import('application.extensions.phpmailer.JPhpMailer');
        $mail = new JPhpMailer;
        $mail->IsSMTP();
        $mail->Host = 'smtp.163.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hu198021688500@163.com';
        $mail->Password = '198502021';
        $mail->SetFrom('hu198021688500@163.com', 'joomtu');
        $mail->Subject = 'PHPMailer Test Subject via smtp, basic with authentication';
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
        $msgHtml = Yii::app()->controller->renderPartial('application.views.user.findpassmailtempl', array(), true);
        $mail->MsgHTML($msgHtml);
        $mail->AddAddress($this->email);
        return $mail->Send();
    }

    public function sendMailUseGmail() {
        Yii::import('application.extensions.phpmailer.JPhpMailer');
        $mail = new JPhpMailer;
        $mail->IsSMTP();
        $mail->SMTPSecure = "ssl";
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = true;
        $mail->Username = 'myusername@gmail.com';
        $mail->Port = '465';
        $mail->Password = '******';
        $mail->SMTPKeepAlive = true;
        $mail->Mailer = "smtp";
        $mail->SMTPAuth = true;
        $mail->CharSet = 'utf-8';
        $mail->SMTPDebug = 0;
        $mail->SetFrom('myusername@gmail.com', 'myname');
        $mail->Subject = 'PHPMailer Test Subject via GMail, basic with authentication';
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
        $mail->MsgHTML('<h1>JUST A TEST!</h1>');
        $mail->AddAddress('to@someone.co.za', 'John Doe');
        $mail->Send();
    }

}