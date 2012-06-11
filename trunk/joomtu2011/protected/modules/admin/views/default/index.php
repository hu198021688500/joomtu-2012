<!-- 
<?php
$this->breadcrumbs=array(
$this->module->id,
);
?>
<h1>
<?php echo $this->uniqueId . '/' . $this->action->id; ?>
</h1>

<?php echo Yii::t('admin', 'test')?>

<p>
	This is the view content for action "
	<?php echo $this->action->id; ?>
	". The action belongs to the controller "
	<?php echo get_class($this); ?>
	" in the "
	<?php echo $this->module->id; ?>
	" module.
</p>
<p>
	You may customize this page by editing
	<tt>
	<?php echo __FILE__; ?>
	</tt>
</p>
 -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="Expires" content="-1000" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
	<title>管理中心 V1.0</title>
</head>

<frameset border=0 frameSpacing=0 rows="60, *" frameBorder=0>
	<frame name=header src="/admin/default/header" frameBorder=0 noResize scrolling=no>
	<frameset cols="600, *">
		<frame name="menu" src="/admin/default/menu" frameBorder="0" noResize>
		<frame name="main" src="/admin/default/main" frameBorder="0" noResize scrolling="yes">
	</frameset>
</frameset>
<noframes>
</noframes>
</HTML>
