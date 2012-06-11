<?php

$this->breadcrumbs = $model->admin->adminBreadcrumbs;
$this->menu = $model->admin->adminMenu;


Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('".strtolower( get_class($model))."-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1 style="text-transform: capitalize;"><?php echo $model->admin->adminHeader;?></h1>

<div class="search-form" style="display:none">
<?php $this->renderPartial($this->searchTemplate,array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'settings-grid',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'columns'=> $model->admin->getGridColumns(
            array(
                'class'=>'CButtonColumn',
            )
        ),
	)
); ?>
