<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'settings-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля помеченные<span class="required">*</span> обязательны.</p>

	<?php echo $form->errorSummary($model); ?>

    <?php foreach ( $model->admin->filterFormFields() as $field ) : ?>
        <div class="row">
            <?php echo $model->getField($field)->asActiveForm($form, array('htmlOptions' =>null));?>
        </div>
    <?php endforeach;?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->