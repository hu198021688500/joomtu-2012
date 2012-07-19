<?php
$this->pageTitle = 'Remind your account password - ' . Yii::app()->name;
$this->breadcrumbs = array('Password');
?>
<?php if (Yii::app()->user->hasFlash('send_email_status')) { ?>
<?php   if (Yii::app()->user->getFlash('send_email_status')) { ?>
<h1>Send success</h1>
<p>
    A message was sent to <?php echo Yii::app()->user->getFlash('send_email_address'); ?> with a link to a page to choose a new password.
    If you do not get that message in 24 hours, please send a e-mail message to info@icontem.com to get further support.Accounts
</p>
<?php   } else { ?>
<h1>Send faild</h1>
<?php   } ?>
<?php } else { ?>
<h1>Get passowrd</h1>
<p>Remind your account password</p>
<div class="form">
    <?php $form = $this->beginWidget('CActiveForm', array('id' => 'login-form', 'enableClientValidation' => true, 'clientOptions' => array('validateOnSubmit' => true))); ?>
    <p class="note">Fields with <span class="required">*</span> are required.</p>
    <p>The site will send you a message.Make sure your are not blocking messages sent from help@icontem.com .</p>
    <p>If you cannot remember your e-mail or access name,send a message to <a href="mailto:info@icontem.com?Subject=Forgot%20my%20password&amp;Body=Hello%2C%0D%0A%0D%0AI%20think%20forgot%20my%20password.%0D%0A%0D%0AI%20think%20my%20access%20name%20is%3A%0D%0A%0D%0AI%20think%20my%20account%20e-mail%20address%20is%3A%0D%0A%0D%0ACan%20you%20help%20me%3F%0D%0A%0D%0AThank%20you.">info@icontem.com</a> to get further help.</p>
    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>
    <?php if (CCaptcha::checkRequirements()) { ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'verifyCode'); ?>
        <?php echo $form->textField($model, 'verifyCode'); ?>
        <div><?php $this->widget('CCaptcha', array('showRefreshButton' => true, 'clickableImage' => true)); ?></div>
        <div class="hint">Please enter the letters as they are shown in the image above.Letters are not case-sensitive.</div>
        <?php echo $form->error($model, 'verifyCode'); ?>
    </div>
    <?php } ?>
    <div class="row buttons"><?php echo CHtml::submitButton('Submit'); ?></div>
    <?php $this->endWidget(); ?>
</div>
<?php } ?>