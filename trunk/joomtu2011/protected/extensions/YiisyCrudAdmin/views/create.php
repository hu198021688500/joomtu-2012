<?php
$this->breadcrumbs= $model->admin->createBreadcrumbs;
$this->menu= $model->admin->createMenu;
?>

<h1><?php echo $model->admin->createHeader;?> </h1>

<?php echo $this->renderPartial($this->formTemplate, array('model'=>$model)); ?>
