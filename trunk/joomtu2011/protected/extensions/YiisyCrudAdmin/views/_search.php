<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

    <?php foreach( $model->admin->searchFields as $field) : ?>
        <div class="row">
            <?php echo $model->getField($field)->asSearchForm($form, array('htmlOptions' => null));?>
        </div>
    <?php endforeach;?>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->