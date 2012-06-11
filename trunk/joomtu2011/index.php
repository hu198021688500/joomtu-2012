<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
// 为应用这些配置，将这个文件的文件名作为一个参数，传递给应用的构造器
Yii::createWebApplication($config)->run();

// 性能调整
/*
 * 开启APC扩展欺缦份劵放
 * 禁用调试模式
 * 使用 yiilite.php，将 yii.php 替换为另一个名为 yiilite.php 的引导文件，需要APC开启
 * 使用缓存技术
 * 数据库优化，使用Dao，不要滥用Active Record
 * 
 * 应用的生命周期
 * 当处理一个用户请求时，一个应用程序将经历如下生命周期：
 * 使用 CApplication::preinit() 预初始化应用。建立类自动加载器和错误处理；注册核心应用组件；载入应用配置；用CApplication::init()初始化应用程序。
 * 注册应用行为；载入静态应用组件；
 * 触发onBeginRequest事件;处理用户请求：
 * 解析用户请求；创建控制器；执行控制器；
 * 触发onEndRequest事件;
 * 
 */
