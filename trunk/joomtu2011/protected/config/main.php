<?php

// the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    // 应用主目录
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    // 应用名称
    'name' => 'JoomTu囧途',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        // srbac访问控制模块
        'application.modules.srbac.controllers.FrontController',
        'application.modules.srbac.controllers.BackController',
        // image模块
        'application.modules.image.components.*',
        'application.modules.image.models.Image',
        // debug扩展
        'ext.debugtb.*', //our extension
        // mongodb扩展
        'ext.YiiMongoDbSuite.*',
        //neo4j
        'ext.neo4j.*',
        //'ext.xx.*'
    ),
    //'sourceLanguage'=>'en_us',
    'language' => 'zh_cn',
    'charset' => 'utf-8',
    // 默认控制器
    'defaultController' => 'site',
    'theme' => 'classic',
    'modules' => array(
        // the following to enable the Gii tool 代码生成工具
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'hugb',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
            'newFileMode' => 0666,
            'newDirMode' => 0777
        ),
        // srbac访问控制模块配置
        'srbac' => array(
            'userclass' => 'User', //default: User
            'userid' => 'id', //default: userid
            'username' => 'username', //default:username
            'delimeter' => '@', //default:-
            'debug' => true, //default :false
            'pageSize' => 15, // default : 15
            'superUser' => 'superAdmin', //default: Authorizer
            'css' => 'srbac.css', //default: srbac.css
            'layout' => 'application.views.layouts.main', //default: application.views.layouts.main,must be an existing alias
            'notAuthorizedView' => 'srbac.views.authitem.unauthorized', // default:srbac.views.authitem.unauthorized, must be an existing alias
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
            'alwaysAllowedPath' => 'srbac.components' // default: srbac.components must be an existing alias
        ),
        // 图片处理模块
        'image' => array(
            'createOnDemand' => true,
            'install' => false
        ),
        // web shell
        'webshell' => array(
            'class' => 'application.modules.webshell.WebShellModule',
            'exitUrl' => '/', // when typing 'exit', user will be redirected to this URL
            // custom wterm options
            'wtermOptions' => array(
                'PS1' => '#', // linux-like command prompt
            ),
            // additional commands (see below)
            'commands' => array(
                'test' => array('js:function(){return "Hello, world!Mr hu!";}', 'Just a test.'),
                // ajax callback to http://yourwebsite/post/index?action=cli (will be normalized according to URL rules)
                'postlist' => array(array('/post/index', array('action' => 'cli')), 'Description.'),
                // sticky command handler. One will need to type 'exit' to leave its context.
                'stickyhandler' => array(
                    array(
                        // optional: called when 'stickyhandler' is typed. Can be either URL array or callback.
                        'START_HOOK' => array('/post/index', array('action' => 'start')),
                        // optional: called when 'exit' is typed. Can be either URL array or callback.
                        'EXIT_HOOK' => "js:function(){ return 'bye!'; }",
                        // required: called when parameter is typed. Can be either URL array or callback.
                        'DISPATCH' => "js:function(tokens){ return 'Hi, Jack!'; }",
                        // optional: custom prompt
                        'PS1' => 'advanced >'
                    ),
                    'Advanced command.'
                )
            ),
            // uncomment to disable yiic
            // 'useYiic'=>false,
            // adding custom yiic commands not from protected/commands dir
            'yiicCommandMap' => array(
                'email' => array(
                    'class' => 'ext.mailer.MailerCommand',
                    'from' => 'sam@rmcreative.ru'
                )
            ),
            // Allowed IPs, localhost by default. Set to false to allow all IPs.
            'ipFilters' => array('127.0.0.1', '::1'),
        // Valid PHP callback that returns if user should be allowed to use web shell.
        // In this example it's valid for PHP 5.3.
        //'checkAccessCallback' => function($controller, $action){return !Yii::app()->user->isGuest;},
        ),
        // 后台管理模块
        'admin' => array('param' => 'test'),
        // 公用模块
        'common' => array(),
        // 安装模块
        'install' => array(),
    ),
    // application components
    'components' => array(
        // CAssetManager - 管理发布私有asset文件
        //'assetManager' => array(),
        // CFormatter - 为显示目的格式化数据值
        //'format'=>array(),
        // CPhpMessageSource - 提供翻译Yii应用程序使用的消息
        //'messages'=>array(),
        // CClientScript - 管理客户端脚本(javascripts and CSS)
        //'clientScript'=>array(),
        // CHttpRequest - 提供和用户请求相关的信息
        //'request'=>array(),
        // CSecurityManager - 提供安全相关的服务，例如散列（hashing）, 加密（encryption）
        //'securityManager'=>array(),
        // CHttpSession - 提供会话（session）相关功能
        //'session'=>array(),
        // CStatePersister -提供全局持久方法（global state persistence method）
        //'statePersister'=>array(),
        // CThemeManager - 管理主题（themes）。
        //'themeManager'=>array(),
        // CCache - 缓存组件,提供数据缓存功能。请注意，您必须指定实际的类（例如CMemCache, CDbCache ） 。否则，将返回空当访问此元件
        'cache' => array(
            'class' => 'system.caching.CMemCache', //system.caching.CFileCache
            'servers' => array(
                array('host' => '192.168.20.30', 'port' => 11211, 'weight' => 4),
                array('host' => '192.168.20.29', 'port' => 11211, 'weight' => 4),
                array('host' => '192.168.20.203', 'port' => 11211, 'weight' => 4)
            )
        ),
        // srbac访问控制模块配置,CAuthManager - 管理基于角色控制 (RBAC)
        'authManager' => array(
            'class' => 'application.modules.srbac.components.SDbAuthManager', // Manager 的类型
            'connectionID' => 'db', //使用的数据库组件
            'itemTable' => 'jt_item', // 授权项目表 (默认:authitem)
            'assignmentTable' => 'jt_assignment', // 授权分配表 (默认:authassignment)
            'itemChildTable' => 'jt_item_children' // 授权子项目表 (默认:authitemchild)
        ),
        // 图片处理模块
        'image' => array(
            'class' => 'ImgManager',
            'versions' => array(
                'small' => array('width' => 120, 'height' => 120),
                'medium' => array('width' => 320, 'height' => 320),
                'large' => array('width' => 640, 'height' => 640)
            )
        ),
        // mogodb扩展
        'mongodb' => array(
            'class' => 'EMongoDB',
            'connectionString' => 'mongodb://localhost',
            'dbName' => 'myDatabaseName',
            'fsyncFlag' => true,
            'safeFlag' => true,
            'useCursor' => false
        ),
        // 攻击防止
        'request' => array(
            //'enableCsrfValidation'=>true, // 跨站请求伪造攻击的防范
            'enableCookieValidation' => true // Cookie攻击验证
        ),
        // CPhpMessageSource - 提供翻译Yii框架使用的核心消息
        'coreMessages' => array(
            'basePath' => 'protected/messages'
        ),
        // CWebUser - 代表当前用户的身份信息
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('user/login'),
        ),
        // the following to enable URLs in path-format
        // CUrlManager - 提供网址解析和某些函数
        'urlManager' => array(
            'urlFormat' => 'path',
            //'caseSensitive'=>true,
            'rules' => array(
                // web shell
                'webshell' => 'webshell',
                'webshell/<controller:\w+>' => 'webshell/<controller>',
                'webshell/<controller:\w+>/<action:\w+>' => 'webshell/<controller>/<action>',
                // other rules
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            )
        ),
        // the following to use a MySQL database
        // CDbConnection - 提供数据库连接。请注意，你必须配置它的connectionString属性才能使用此元件
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=joomtu',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'admin',
            'charset' => 'utf8',
            'tablePrefix' => 'jt_'
        ),
        'db_slave' => array(
            'class' => 'system.db.CDbConnection',
            'connectionString' => 'mysql:host=192.168.20.30;port=3306;dbname=mocube',
            'emulatePrepare' => true,
            'enableParamLogging' => true,
            'username' => 'mocube',
            'password' => 'mocube',
            'charset' => 'utf8',
            'tablePrefix' => 'mb_'
        ),
        // CErrorHandler - 处理没有捕获的PHP错误和例外
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error'
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute', // CDbLogRoute,CEmailLogRoute,CFileLogRoute,CWebLogRoute,CProfileLogRoute
                    'levels' => 'error, warning, profile, info, trace'
                //'logFile'=>'infoMessages.log'
                ),
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
            )
        )
    ),
    //'catchAllRequest' => array('site/index','lang'=>'en'),
    /* 'onBeginRequest' => function($event){
      if ('127.0.0.1' === $_SERVER['REMOTE_ADDR']){
      Yii::app()->catchAllRequest = null;
      }
      }, */
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'siteUrl' => 'http://www.joomtu.dev',
        'siteRes' => 'http://www.joomtu.dev',
        'fileRes' => 'http://www.joomtu.dev',
        'siteResServers' => array(),
        'fileServers' => array(),
    )
);