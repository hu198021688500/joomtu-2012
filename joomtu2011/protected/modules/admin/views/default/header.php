<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
	<title>管理中心 V1.0</title>
</head>

<body>
	<table cellSpacing=0 cellPadding=0 width="100%" background="/images/header_bg.jpg" border=0>
		<tr height=56>
			<td width=260><img height=56 src="/images/header_left.jpg" width=260></td>
			<td style="font-weight: bold; color: #fff; padding-top: 20px" align=middle>当前用户：admin &nbsp;&nbsp;
				<a style="COLOR: #fff" href="" target=main>修改口令</A>&nbsp;&nbsp;
				<a style="COLOR: #fff" onclick="if(confirm('确定要退出吗？')) return true; else return false;" href="" target=_top>退出系统</A>
			</td>
			<td align=right width=268><img height=56 src="/images/header_right.jpg" width=268></td>
		</tr>
	</table>
	<table cellSpacing=0 cellPadding=0 width="100%" border=0>
		<tr bgColor=#1c5db6 height=4>
			<td></td>
		</tr>
	</table>
</body>
</html>
