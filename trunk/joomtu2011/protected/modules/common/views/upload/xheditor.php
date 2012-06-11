<?php
$this->breadcrumbs=array(
	'Upload'=>array('/common/upload'),
	'Xheditor',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>

<?php $this->widget('application.extensions.xheditor.JXHEditor', array(
    'model' => $model,
    'attribute' => 'content',
	'options'=>array(
        /*'id'=>'xh1',
        'name'=>'xh',
        'tools'=>'simple', // mini, simple, full or from XHeditor::$_tools
        'width'=>'100%',
        'skin'=>'o2007silver',
        'emot'=>'msn',*/
        'upImgUrl'=> 'create' // the action name in the controller
        
		//see XHeditor::$_configurableAttributes for more
	),
    'htmlOptions'=>array('cols'=>80,'rows'=>20,'style'=>'width: 100%; height: 500px;'),
));?>