<!DOCTYPE html>
<html>
<head>
	<base id="web_base" href="<?php echo Yii::app()->params->host;?>" />
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<meta name="keywords" content="ddd" />
	<meta name="description" content="ddd" />
	<meta name="author" content="Mobo360.com" />
	<meta property="qc:admins" content="22613757756574161676375"/>
	<title><?php echo CHtml::encode($this->pageTitle);?></title>
	<link href="favicon.ico" rel="shortcut icon" />
</head>

<body>
	<?php echo $content; ?>
</body>
</html>
