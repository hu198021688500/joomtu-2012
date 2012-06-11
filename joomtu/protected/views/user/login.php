<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>
<p>If you have not account, please click <a href="/user/register">register</a></p>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
		<p class="hint">
			Hint: You may login with <tt>admin@admin.com/admin</tt> or <tt>hu198021688500@163.com/19850202</tt>.
		</p>
	</div>
	
	<?php if(Yii::app()->params['loginCaptcha']&&CCaptcha::checkRequirements()){?>
	<div class="row">
		<?php echo $form->labelEx($model,'verifyCode');?>
		<?php echo $form->textField($model,'verifyCode');?>
		<div>
			<?php $this->widget('CCaptcha',array('showRefreshButton'=>true));?>
		</div>
		<div class="hint">Please enter the letters as they are shown in the image above.<br/>Letters are not case-sensitive.</div>
		<?php echo $form->error($model,'verifyCode');?>
	</div>
	<?php }?>

	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>

<?php $this->endWidget(); ?>
</div><!-- form -->
