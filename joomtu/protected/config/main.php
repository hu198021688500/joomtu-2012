<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Joomtu 2012',
    //网站语言 多语言设置，对应 protected/messages/zh_cn
    //'language' => 'zh_cn',
    //时区设置
    'timeZone' => 'Asia/Shanghai',
    //默认访问 即访问index.php时会自动跳转到某个controller
    'defaultController' => 'site',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.forms.*',
        'application.models.*',
        'application.behaviors.*',
        'application.components.*',
        'application.modules.srbac.controllers.SBaseController',
        // debug扩展
        'ext.debugtb.*',
    ),
    //模块设置 设置后，同名module优先级高于contoller
    'modules' => array(
        // the following to enable the Gii tool 代码生成工具
        // 开启GII模块，不使用必须注释掉。
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'hugb',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'newFileMode' => 0666,
            'newDirMode' => 0777
        ),
        'admin' => array(),
        'srbac' => array(
            'userclass' => 'User', //default: User
            'userid' => 'uid', //default: userid
            'username' => 'email', //default:username
            'delimeter' => '@', //default:-
            'debug' => true, //default :false
            'pageSize' => 10, // default : 15
            'superUser' => 'Authority', //default: Authorizer
            'css' => 'srbac.css', //default: srbac.css
            'layout' => 'application.views.layouts.main', //default: application.views.layouts.main,must be an existing alias
            'notAuthorizedView' => 'srbac.views.authitem.unauthorized', // default:srbac.views.authitem.unauthorized,must be an existing alias
            'alwaysAllowed' => array(//default: array()
                'SiteLogin', 'SiteLogout', 'SiteIndex', 'SiteAdmin',
                'SiteError', 'SiteContact'
            ),
            'userActions' => array('Show', 'View', 'List'), //default: array()
            'listBoxNumberOfLines' => 15, //default : 10
            'imagesPath' => 'srbac.images', // default: srbac.images
            'imagesPack' => 'noia', //default: noia
            'iconText' => true, // default : false
            'header' => 'srbac.views.authitem.header', //default : srbac.views.authitem.header,must be an existing alias
            'footer' => 'srbac.views.authitem.footer', //default: srbac.views.authitem.footer,must be an existing alias
            'showHeader' => true, // default: false
            'showFooter' => true, // default: false
            'alwaysAllowedPath' => 'srbac.components', // default: srbac.components must be an existing alias
        )
    ),
    // application components
    'components' => array(
        'SAEOAuth' => array(
            'WB_AKEY' => '1500340182',
            'WB_SKEY' => 'c09b0ad5183707679d79e8bc24259c8c',
            'callback' => '/site/callback',
            'class' => 'SAEOAuth',
        ),
        'user' => array(
            // 允许cookie自动登录 并保存到runtime/state.bin
            'allowAutoLogin' => true,
        // session 前缀,单点登录与区分前后台登录时可以用到
        #'stateKeyPrefix'=> 'f_site',
        // 登录链接 Yii::app()->user->loginUrl
        #'loginUrl'=> array('/site/login'),
        //cookie 验证
        #'identityCookie'=>array('domain'=>'.'.ALL_DOMAIN,),
        //自动刷新 cookie
        #'autoRenewCookie'=>true,
        ),
        'request' => array(
            'baseUrl' => '',
            //Cookie攻击的防范
            'enableCookieValidation' => true,
        //跨站请求伪造(简称CSRF)攻击 防范
        #'enableCsrfValidation'=>true,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            // 静态化
            //'urlSuffix'=>'.html',
            //路径模式的URL,方便SEO,搜索引擎搜索
            //'urlFormat' => 'path',
            //不显示脚本名 index.php
            //'showScriptName' => false,
            //主域名 直接访问controllers
            #'baseUrl'=>'http://'.SUB_DOMAIN_main,
            /* 'rules' => array(
              'post/<id:\d+>/<title:.*?>' => 'post/view',
              'posts/<tag:.*?>' => 'post/index',
              //assets目录发布到web，使用path路径，浏览器会认为是静态文件*达到http304的目的
              'assets/<path:.*?>' => 'site/assets',
              '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
              ), */
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => array(
            'class' => 'CDbConnection',
            'connectionString' => 'mysql:host=192.168.35.188;dbname=joomtu;port=3306',
            'username' => 'root',
            'password' => 'admin',
            'charset' => 'utf8',
            'tablePrefix' => 'jt_',
            'autoConnect' => false,
            'emulatePrepare' => true,
            'enableParamLogging' => YII_DEBUG,
            'schemaCachingDuration' => 54000, // 15 minutes,
//            'connectionString' => 'mysql:host=192.168.20.29;port=3306;dbname=mocube',
//            'emulatePrepare' => true,
//            'enableParamLogging' => true,
//            'username' => 'mocube',
//            'password' => 'mocube',
//            'charset' => 'utf8',
//            'tablePrefix' => 'mb_',
//            //配置为 SAEDbConnection 则不必考虑用户名密码 并自动读写分离
//            #'class'=>'SAEDbConnection',
//            'connectionString' => 'mysql:host=localhost;port=3306;dbname=app_yiis',
//            'username' => 'root',
//            'password' => '111111',
//            #'connectionString' => 'sqlite:protected/data/blog.db',
//            'charset' => 'utf8',
//            'tablePrefix' => 'tbl_',
//            'emulatePrepare' => true,
//            'schemaCachingDuration' => 3600,
        ),
        'session' => array(
            'class' => 'CDbHttpSession',
            'connectionID' => 'db',
        #'cookieParams' => array('domain' => '.'.ALL_DOMAIN, 'lifetime' => 0),
        #'timeout' => 3600,
        #'sessionName' => 'session'
        ),
        /*
          'master' => array(
          'class' => 'CDbConnection',
          'connectionString' => 'mysql:host=<slave ip>;dbname=<dbname>;port=3306',
          'username' => '<username>',
          'password' => '<password>',
          'autoConnect' => false,
          'enableParamLogging' => YII_DEBUG,
          'schemaCachingDuration' => 54000 // 15 minutes
          ),
          'fileDb'=>array(
          'class' => 'CDbConnection',
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'authManager' => array(
            // Path to SDbAuthManager in srbac module if you want to use case insensitive access checking (or CDbAuthManager for case sensitive access checking)
            'class' => 'application.modules.srbac.components.SDbAuthManager',
            // The database component used
            'connectionID' => 'db',
            // The itemTable name (default:authitem)
            'itemTable' => 'jt_items',
            // The assignmentTable name (default:authassignment)
            'assignmentTable' => 'jt_assignments',
            // The itemChildTable name (default:authitemchild)
            'itemChildTable' => 'jt_itemchildren',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    /*
                      //SAE 不支持直接本地IO 改为db记录
                      'class'=>'CDbLogRoute',
                      'connectionID'=>'db',
                      'levels'=>'error, warning',
                     */
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                /*
                  array(
                  'class'=>'CWebLogRoute',
                  ),
                 */
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'system.db.CDbCommand',
                    //'levels'=>'error',
                    'showInFireBug' => true
                ),
                // the following to show log messages on web pages 在页面上显示日志
                array(
                    'class' => 'CWebLogRoute',
                    'showInFireBug' => true
                ),
                // bug
                array(
                    'class' => 'XWebDebugRouter',
                    'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
                    'levels' => 'error, warning, trace, profile, info',
                    'allowedIPs' => array('127.0.0.1', '::1', '192.168.1.54', '192\.168\.1[0-5]\.[0-9]{3}')
                )
            ),
        /*
          'assetManager' => array(
          'class' => 'SAEAssetManager',
          //此处填写你在 SAE Storage 中创建得domain
          'domain'=> 'assets',
          ),
         */
        /*
          'cache'=>array(
          //如果没有必要，不用修改缓存配置。 SAE不支持本地文件的IO处理 已经提供了memcache
          'class'=>'CFileCache',
          ),
         */
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'siteUrl' => 'http://dev.joomtu.com/',
        'neo4jRestUrl' => 'http://host185.freebsd.hu:7474/db/data/',
        'registerCaptcha' => false,
        'loginCaptcha' => false
    ),
);

/*
//如果定义了常量，则默认为在SAE环境中
if(defined('SAE_TMP_PATH'))
{
    //SAE 不支持I/O
    $config['runtimePath'] = SAE_TMP_PATH;
    //配置为 SAEDbConnection 则不必考虑用户名密码 并自动读写分离
    $config['components']['db'] = array(
            'class'=>'SAEDbConnection',
            'charset' => 'utf8',
        'tablePrefix'=>'tbl_',
            'emulatePrepare' => true,
            //开启sql 记录
            'enableProfiling'=>true,
            'enableParamLogging'=>true,
            //cache
            'schemaCachingDuration'=>3600,
    );
    //SAE不支持I/O 使用storage 存储 assets。 如果在正式环境，请将发布到assets的css/js做合并，直接放到app目录下，storage的分钟限额为5000，app为200000
    //最新的SAE 不使用storage 而是在siteController中，导入了一个SAEAssetsAction，通过 site/assets?path=aaa.txt ，将文件内容输出到web端，来访问实际的 aaa.txt 文件，
    $config['components']['assetManager'] = array('class' => 'SAEAssetManager','domain'=> 'assets');
    //如果没有必要，不用修改缓存配置。 SAE不支持本地文件的IO处理 已经提供了memcache
    $config['components']['cache'] = array(
            'class'=> 'SAEMemCache',
            'servers'=>array(
                array('host'=>'localhost', 'port'=>11211, 'weight'=>100),
            ),
        );

}
return $config;
 *
 */