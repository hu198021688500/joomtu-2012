<?php
$this->breadcrumbs= $model->admin->updateBreadcrumbs;
$this->menu= $model->admin->updateMenu;
?>

<h1><?php echo $model->admin->updateHeader;?></h1>

<?php echo $this->renderPartial($this->formTemplate, array('model'=>$model)); ?>
