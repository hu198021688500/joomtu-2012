<?php
/**
 *	上传widget
 *	@author		hugb <hu198021688500@163.com>
 *	@copyright	2011-2012
 *	@version	1.0
 *	@package	protected.widgets
 *
 *	$Id$
 */

class SwfUpload extends CWidget{
	
	public $spanClass = 'input-file';
	public $defaultConfig = null; // 默认的文件上传配置
	public $jsHandlerUrl= null; // 包含事件监听处理函数的js文件
	
	public $postParams = array(); // 需要POST的数据
	public $config = array(); // 调用时传入的参数配置
	
	public function init(){
		$this->defaultConfig = array(
			// 上传的文件地址
			'upload_url'=>'',
			'file_post_name'=>'file',
			// 上传文件附带的post数据
			'post_params'=>array('PHPSESSID'=>session_id()),
			// 文件设置
			'file_size_limit'=>5120, // 5MB
			'file_types'=>'*.jpg;*.png;*.gif',
			'file_types_description'=>'Image Files',
			'file_upload_limit'=>10,
			'file_queue_limit'=>100,
			// 事件监听设置
			//'swfupload_loaded_handler'=>'js:swfupload_loaded_function',
			'file_dialog_start_handler'=>'js:fileDialogStart',
			//'file_queued_handler'=>'js:fileQueued',
			//'file_queue_error_handler'=>'js:fileQueueError',
			'file_dialog_complete_handler'=>'js:fileDialogComplete',
			'upload_start_handler'=>'js:uploadStart',
			//'upload_progress_handler'=>'js:uploadProgress',
			'upload_error_handler'=>'js:uploadError',
			'upload_success_handler'=>'js:uploadSuccess',
			'upload_complete_handler'=>'js:uploadComplete',
			// 上传按钮设置
			'button_image_url'=>'',
			'button_text'=>'<span class="button">'.Yii::t('messageFile', 'ButtonLabel').' (Max 2 MB)</span>',
			//'button_text_style'=>'.button { font-family: "Lucida Sans Unicode", "Lucida Grande", sans-serif; font-size: 11pt; text-align: center; }',
			//'button_action'=>'SWFUpload.BUTTON_ACTION.SELECT_FILES',
			//'button_disabled'=>false,
			//'button_cursor'=>'SWFUpload.CURSOR.ARROW',
			//'button_window_mode'=>'SWFUpload.WINDOW_MODE.OPAQUE',
			'button_placeholder_id'=>'swfupload',
			'button_width'=>105,
			'button_height'=>30,
			//'button_text_top_padding'=>0,
			//'button_text_left_padding'=>0,
			//'button_window_mode'=>'js:SWFUpload.WINDOW_MODE.TRANSPARENT',
			//'button_cursor'=>'js:SWFUpload.CURSOR.HAND',
			// flash地址
			'flash_url'=>'',
			'flash_width'=>105,
			'flash_height'=>30,
			//'flash_color'=>'#FFFFFF',
			// 自定义
			'custom_settings'=>array(/*'upload_target'=>'divFileProgressContainer'*/),
			// 调试模式
			'debug'=>false,
			//'debug_handler'=>'js:debug_function',
			'use_query_string'=>true,
			'requeue_on_error'=>false,
			'prevent_swf_caching'=>false,
			'preserve_relative_urls'=>false
		);
		foreach ($this->config as $key=>$value) {
			if (isset($this->defaultConfig[$key])){
				$this->defaultConfig[$key] = $value;
			}
		}
		if(isset($this->postParams)){
			$this->defaultConfig['post_params'] = array_merge($this->defaultConfig['post_params'],$this->postParams);
		}
	}
	
	public function run(){
		$assets = dirname(__FILE__).'/assets';
		$baseUrl = Yii::app()->assetManager->publish($assets);
		
		if(isset($this->jsHandlerUrl)){
			Yii::app()->clientScript->registerScriptFile($baseUrl.'/'.$this->jsHandlerUrl);
			unset($this->jsHandlerUrl);
		}
		
		$this->defaultConfig['flash_url'] = $baseUrl. '/swfupload.swf';
		
		//Yii::app()->getClientScript()->registerCssFile($baseUrl.'/default.css');
		Yii::app()->clientScript->registerScriptFile($baseUrl.'/swfupload.js',CClientScript::POS_HEAD);
		
		$config = CJavaScript::encode(array_merge($this->defaultConfig, $this->config));
		Yii::app()->getClientScript()->registerScript(__CLASS__, "var swfu;swfu = new SWFUpload($config);");
	}  
}
?>