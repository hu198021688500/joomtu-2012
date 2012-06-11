<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/tr/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
	<title>管理中心登陆 V1.0</title>
</head>

<body class="login">
	<div id=warp>
		<div><img height="23" src="/images/login_1.jpg" width="468" /></div>
		<div><img height="147" src="/images/login_2.jpg" width="468" /></div>
		<div>
			<table cellSpacing="0" cellPadding="0" width="468" border="0" bgcolor="#ffffff">
				<tr>
					<td width="16"><img height="122" src="/images/login_3.jpg" width="16"></td>
					<td align="middle">
						<table cellSpacing="0" cellPadding="0" width="230" border="0">
							<form name="form1" action="" method="post">
							<tr height="5">
								<td width="5"></td>
								<td width="56"></td>
								<td></td>
							</tr>
							<tr height="36">
								<td></td>
								<td>用户名</td>
								<td><input maxLength="30" size="24" value="" name="name" /></td>
							</tr>
							<tr height="36">
								<td>&nbsp; </td>
								<td>口　令</td>
								<td><input type="password" maxLength="30" size="24" value="" name="pass" /></td>
							</tr>
							<tr height="5">
								<td colSpan="3"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td><input type="image" height="18" width="70" src="/images/bt_login.gif"></td>
							</tr>
							</form>
						</table>
					</td>
					<td width="16"><img height="122" src="/images/login_4.jpg" width="16"></td>
				</tr>
			</table>
		</div>
		<div><img height="16" src="/images/login_5.jpg" width="468" /></div>
	</div>
</body>
</html>
