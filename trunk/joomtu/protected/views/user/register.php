<?php
$this->pageTitle='Register - ' . Yii::app()->name;
$this->breadcrumbs=array('Register');
?>

<h1>Rgister</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
<?php
$form=$this->beginWidget('CActiveForm',array(
	'id'=>'user-register-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array('validateOnSubmit'=>true),
	'focus'=>array($model, 'email')
));
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model);?>

	<div class="row">
		<?php echo $form->labelEx($model,'email');?>
		<?php echo $form->textField($model,'email',array('maxlength'=>32));?>
		<?php echo $form->error($model,'email');?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'password');?>
		<?php echo $form->passwordField($model,'password',array('maxlength'=>32));?>
		<?php echo $form->error($model,'password',array(),false);?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'confirmPwd');?>
		<?php echo $form->passwordField($model,'confirmPwd',array('maxlength'=>32));?>
		<?php echo $form->error($model,'confirmPwd',array(),false);?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name');?>
		<?php echo $form->textField($model,'name',array('maxlength'=>10));?>
		<?php echo $form->error($model,'name',array(),false);?>
	</div>

	<?php if(Yii::app()->params['registerCaptcha']&&CCaptcha::checkRequirements()){?>
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
		<?php echo $form->checkBox($model,'agree');?>
		<?php echo $form->label($model,'agree');?>
		<?php echo $form->error($model,'agree',array(),false);?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Resiter');?>
	</div>

<?php $this->endWidget();?>
</div><!-- form -->