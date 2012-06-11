<?php
$this->breadcrumbs=array(
	'Upload',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>

<?php
$this->widget('application.extensions.swfupload.SwfUpload', array(
	'jsHandlerUrl'=>'handler.js', //Relative path
	'config'=>array(
		'upload_url'=>'common/upload/save',//Use $this->createUrl method or define yourself
	))
);
?>
 
<?php echo CHtml::beginForm(); ?>
<div class="form">
    <div class="row">
    <div id="divFileProgressContainer"></div>
    <div class="swfupload"><span id="swfupload"></span></div>
    </div>
</div>
<?php echo CHtml::endForm(); ?>
