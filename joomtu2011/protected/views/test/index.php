<?php /*echo CGoogleApi::init();
	echo CHtml::script(
    CGoogleApi::load('jquery','1.6.2') . "\n" .
    CGoogleApi::load('jquery.ajaxqueue.js') . "\n" .
    CGoogleApi::load('jquery.metadata.js')
);*/?>
<h3><?php echo $time; ?></h3>
<?php //$this->widget('ext.WSocialButton', array('style'=>'box'));?>
<?php /*Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
	$this->widget('CJuiDateTimePicker',array(
		'model'=>$model, //Model object
		'attribute'=>'startTime', //attribute name
		'mode'=>'datetime', //use "time","date" or "datetime" (default)
		'options'=>array() // jquery plugin options
	));*/
?>
<?php //$this->widget('application.extensions.tinymce.ETinyMce', array('name'=>'html')); ?>
<p><?php echo CHtml::link("Hello",array('user/index'));?></p>
<?php //Yii::app()->assetManager();
$this->breadcrumbs=array(
	'Test',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>

<?php echo Yii::t('admin', 'hugb')?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$dataProvider, // 数据源
	'pager'=>array( // 通过pager设置样式   默认为CLinkPager
		'prevPageLabel'=>'上一页',
		'firstPageLabel'=>'首页', // first,last 在默认样式中为{display:none}及不显示，通过样式{display:inline}即可
		'nextPageLabel'=>'下一页',
		'lastPageLabel'=>'末页',
		'header'=>'',
	),
	'ajaxUpdate'=>true, //是否使用ajax分页 null为ajax分页
	'columns'=>array(
		/*array( // 具体设置每列的header
			'name'=>'ID',
			'value'=>'$data->id',
		),
		'username',
		array(
			'email'=>'邮件地址', // 需要连接可使用CLinkColumn
			'value'=>'$data->email',
		),
		array(
			'name'=>'模型',
			'value'=>'$data->module',
		),
		array( // 自定义按钮操作列
			'header'=>'操作',
			'buttons'=>array(
				'preview'=>array(
					'label'=>'审核',
					'url'=>'', //通过PHP表达式生成URL例如createUrl
					'imageUrl'=>'', // 按钮图片地址
					'options'=>array(), // HTML标签属性设置
					'click'=>'', // js代码或函数
					'visible'=>'', // PHP表达式 用于权限控制
				),
				'recommend'=>array(
					'label'=>'推荐',
				),
			),
			'class'=>'CButtonColumn',
			'template'=>'{preview}&nbsp;&nbsp;{recommend}',
		),
		array(// 仅使用默认CButtonColumn不具体设置按钮，在显示为查看 修改 删除图片按钮
			'class'=>'CButtonColumn',
		),*/
	)
));
?>

<?php
/*$this->widget('application.extensions.menu.SMenu',
array(
"menu"=>array(
array("url"=>array(
"route"=>"/product/create"),
"label"=>"Sspiner",
array("url"=>array(
"route"=>"/product/create"),
"label"=>"Create product",),
array("url"=>array(
"route"=>"/product/list"),
"label"=>"Product List",),
array("url"=>"",
"label"=>"View Products",
array("url"=>array(
"route"=>"/product/show",
"params"=>array("id"=>3),
"htmlOptions"=>array("title"=>"title")),
"label"=>"Product 3"),
array("url"=>array(
"route"=>"/product/show",
"params"=>array("id"=>4)),
"label"=>"Product 4",
array("url"=>array(
"route"=>"/product/show",
"params"=>array("id"=>5)),
"label"=>"Product 5")))),
array("url"=>array(
"route"=>"/event/create"),
"label"=>"Scalendar"),
array("url"=>array(),
"label"=>"Admin",
array("url"=>array(
"route"=>"/event/admin"),
"label"=>"Scalendar Admin"),
array("url"=>array(
"route"=>"/product/admin"),
"label"=>"Sspinner Admin"),
array("url"=>array(
"route"=>"/product/admin"),
"label"=>"Disabled Link",
"disabled"=>true)),
array("url"=>array(),
"label"=>"Documentation",
array("url"=>array(
"link"=>"http://www.yiiframework.com",
"htmlOptions"=>array("target"=>"_BLANK")),
"label"=>"Yii Framework"),
array("url"=>array(
"route"=>"site/spinnerDoc"),
"label"=>"Sspinner"),
array("url"=>array(
"route"=>"site/calendarDoc"),
"label"=>"Scalendar"),
array("url"=>array(
"route"=>"site/menuDoc"),
"label"=>"Smenu"),
)
),
"stylesheet"=>"menu_blue.css",
"menuID"=>"myMenu",
"delay"=>3
)
);*/
$this->widget('ext.ztree.zTree', array(
        'treeNodeNameKey'=>'name',
        'treeNodeKey'=>'id',
        'treeNodeParentKey'=>'pId',
        'options'=>array(
                'expandSpeed'=>"",
                'showLine'=>true,
        ),
        'data'=>array(
                array('id'=>1, 'pId'=>0, 'name'=>'目录1'),
                array('id'=>2, 'pId'=>1, 'name'=>'目录2'),
                array('id'=>3, 'pId'=>1, 'name'=>'目录3'),
                array('id'=>4, 'pId'=>1, 'name'=>'目录4'),
                array('id'=>5, 'pId'=>2, 'name'=>'目录5'),
                array('id'=>6, 'pId'=>3, 'name'=>'目录6')
        )
));
?>
