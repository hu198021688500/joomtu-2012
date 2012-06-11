<?php
$this->breadcrumbs = $model->admin->viewBreadcrumbs;
$this->menu = $model->admin->viewMenu;
?>

<h1><?php echo $model->admin->viewHeader;?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=> $model->admin->viewAttributes,
)); ?>
