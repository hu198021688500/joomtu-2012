<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>joomtu - 安装向导</title>
<link type="text/css" rel="stylesheet" href="css/install.css" />
<script type="text/javascript">
	function $(id) {
		return document.getElementById(id);
	}

	function showMsg(msg) {
		var notice = $('notice');
		notice.value += msg + "\r\n";
		notice.scrollTop = notice.scrollHeight;
	}
</script>
</head>
<body>
	<div class="head"></div>
	<div class="nav nav_<?php echo $step;?>"></div>
	<div class="main">
		<div class="top"></div>
		<div class="center">
			<?php echo $content;?>
			<div class="clear"></div>
		</div>
		<div class="bottom"></div>
	</div>
	<strong>Powered by joomtu V1.0 &copy; 2011-2015 </strong>
	<br />
	<br />
</body>
</html>