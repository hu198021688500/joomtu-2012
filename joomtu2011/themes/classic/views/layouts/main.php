<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<!--
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/reset.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/common.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/activity.css" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<?php $is_login = TRUE;$controllerName="";?>
<!--=S 头部 -->
	<div id="header">
		<div class="headerT">
			<div class="headerW clearfix">
				<div class="ht-login">
					<?php if (Yii::app()->user->isGuest):?>
					<span class="ht-login-setUP">
						<a href="/user/login">登录</a>|
						<a href="/user/register">注册</a>
					</span>
					<?php else:?>
					<span class="ht-login-name">欢迎回家，<?php echo Yii::app()->user->name;?>！</span>
					<span class="ht-login-setUP">
						<a href="">站内信(<em class="pngIcon">20</em>)</a>|
						<a href="">好友关系</a>|
						<a href="">帐号设置</a>|
						<a href="/user/logout">退出</a>
					</span>
					<?php endif;?>
				</div>
				<div class="ht-search clearfix pngIcon">
					<input type="text" class="hts-text" value="搜索活动"
						onfocus="if (value=='搜索活动'){value='';this.style.color='#333'}"
						onblur="if(!value){value=defaultValue;this.style.color='#ccc'}" />
					<input type="button" value="" class="hts-button" />
				</div>
			</div>
		</div>
		<div class="headerB pngIcon">
			<div class="headerW clearfix">
				<div class="hb-logo">
					<a href=""><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/secIMG/common/logo.png" alt="MO立方LOGO" class="pngIcon" /></a>
					<h1 class="bizH1 pngIcon">商街</h1>
				</div>
				<div class="hb-menu">
					<?php
						$this->widget('zii.widgets.CMenu',array(
							'activeCssClass'=>'on',
							'items'=>array(
								array('label'=>'首页', 'url'=>array('site/index')),
								array('label'=>'我的家', 'url'=>array('home/index')),
								array('label'=>'话题', 'url'=>array('topic/index')),
								array('label'=>'活动', 'url'=>array('activity/index')),
								array('label'=>'商街', 'url'=>array('shop/index')),
								array('label'=>'下载', 'url'=>array('activity/index')),
								array('label'=>'串门', 'url'=>array('activity/index'))
							),
							'htmlOptions'=>array('class'=>'hb-menu-ul clearfix')
						));
					?>
				</div>
			</div>
		</div>
	</div>
	<!--=E头部-->
	
	<!--=S 内容主体 -->
	<div id="contenter"><?php echo $content; ?></div>
	<!--=E内容主体 -->

	<!--=S 底部 -->
	<div id="footer">
		<a href="">Mobo360.com</a>|<a href="">意见反馈</a>|<a href="">帮助</a>蜀ICP备11013060号
	</div>
	<!--=E底部-->
	
</body>
</html>