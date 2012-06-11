<?php
/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
return array(
	'sourcePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'messagePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'messages',
	'languages'=>array('zh_cn','zh_tw','de','el','es','sv','he','nl','pt','pt_br','ru','it','fr','ja','pl','hu','ro','id','vi','bg','lv','sk'),
	'fileTypes'=>array('php'),
    'overwrite'=>true,
	'exclude'=>array(
		'.svn',
		'yiilite.php',
		'yiit.php',
		'/i18n/data',
		'/messages',
		'/vendors',
		'/web/js',
	),
);

/**
 * 假设：你的程序源语言为英文，需要制作简体中文版。
 * 1、复制framework\messages\config.php 文件到 protected\messages\下
 * 2、更改config.php 'languages'=>array('zh_cn',)
 * 3、打开命令行工具 ,进入framework 目录 ,执行yiic message "..\protected\messages\config.php"
 * 在protected\messages\下会生产zh_cn 目录，若发现为空，是因为你没有需要翻译的内容。接下来：
 * 4、找到你需要翻译的视图文件 如：Yii 自带blog 例子 post\_view.php 中 <b>Tags:</b>
 * 改为 <b> <? php echo Yii::t('post','Tags') </b> 再执行命令上述命令 就可以看到zh_cn 目录下post.php 文件了。翻译之。
 * 5、更改protected\config\main.php
 * 'language'=>'zh_cn',
 * 'sourceLanguage'=>'en_us',
 * 完毕，即可看到效果。
 */