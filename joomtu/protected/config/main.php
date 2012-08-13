<?php

Yii::setPathOfAlias('local', '/var/www/joomtu-2012/joomtu');
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Joomtu 2012',
    //'language' => 'zh_cn',
    'timeZone' => 'Asia/Shanghai',
    'defaultController' => 'site',
    'preload' => array('log'),
    'import' => array(
        'application.forms.*',
        'application.models.*',
        'application.behaviors.*',
        'application.components.*',
        'application.modules.srbac.controllers.SBaseController',
        'ext.debugtb.*'
    ),
    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'hugb',
            'ipFilters' => array('127.0.0.1', '::1'),
            'newFileMode' => 0666,
            'newDirMode' => 0777
        ),
        'admin' => array(),
        'srbac' => array(
            'userclass' => 'User',
            'userid' => 'uid',
            'username' => 'email',
            'delimeter' => '@',
            'debug' => true,
            'pageSize' => 10,
            'superUser' => 'Authority',
            'css' => 'srbac.css',
            'layout' => 'application.views.layouts.main',
            'notAuthorizedView' => 'srbac.views.authitem.unauthorized',
            'alwaysAllowed' => array(
                'SiteLogin', 'SiteLogout', 'SiteIndex', 'SiteAdmin',
                'SiteError', 'SiteContact'
            ),
            'userActions' => array('Show', 'View', 'List'),
            'listBoxNumberOfLines' => 15,
            'imagesPath' => 'srbac.images',
            'imagesPack' => 'noia',
            'iconText' => true,
            'header' => 'srbac.views.authitem.header',
            'footer' => 'srbac.views.authitem.footer',
            'showHeader' => true,
            'showFooter' => true,
            'alwaysAllowedPath' => 'srbac.components'
        )
    ),
    'components' => array(
        'SAEOAuth' => array(
            'WB_AKEY' => '1500340182',
            'WB_SKEY' => 'c09b0ad5183707679d79e8bc24259c8c',
            'callback' => '/site/callback',
            'class' => 'SAEOAuth'
        ),
        'user' => array(
            'allowAutoLogin' => true,
        // session 前缀,单点登录与区分前后台登录时可以用到
        #'stateKeyPrefix'=> 'f_site',
        // 登录链接 Yii::app()->user->loginUrl
        'loginUrl'=> array('/user/login'),
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
        )
    ),
    // using Yii::app()->params['paramName']
    'params' => array(
        'adminEmail' => 'webmaster@example.com',
        'siteUrl' => 'http://dev.joomtu.com/',
        'neo4jRestUrl' => 'http://host185.freebsd.hu:7474/db/data/',
        'registerCaptcha' => false,
        'loginCaptcha' => false
    ),
);