<?php
$this->breadcrumbs=array(
	'Default'=>array('/install/default'),
	'StepTwo',
);?>
<h3>设置数据库链接信息</h3>
				<table class="data_set">
					<tr>
						<th colspan="3"></th>
					</tr>
					<tr>
						<td width="14%">数据库地址</td>
						<td width="37%"><input type="text" class="setup_input"
							name="dbhost" value="localhost" /></td>
						<td width="49%" class="lightcolor">数据库服务器地址，一般为localhost</td>
					</tr>
					<tr>
						<th colspan="3"></th>
					</tr>
					<tr>
						<td>数据库名称</td>
						<td><input type="text" class="setup_input" name="dbname"
							value="iwebsns" /></td>
						<td class="lightcolor"><input name="create" type="checkbox"
							id="create" value="1" />&nbsp;&nbsp;如果果不存在，则自动被创建</td>
					</tr>
					<tr>
						<th height="13" colspan="3"></th>
					</tr>
					<tr>
						<td>数据库用户名</td>
						<td><input type="text" class="setup_input" name="dbuser"
							value="root" /></td>
						<td class="lightcolor">您的MySQL 用户名</td>
					</tr>
					<tr>
						<th colspan="3"></th>
					</tr>
					<tr>
						<td>数据库密码</td>
						<td><input type="password" class="setup_input" name="dbpw"
							value="" /></td>
						<td class="lightcolor">您的MySQL密码</td>
					</tr>
					<tr>
						<th colspan="3"></th>
					</tr>
					<tr>
						<td>数据表前缀</td>
						<td><input type="text" class="setup_input" name="tablepre"
							value="isns_" /></td>
						<td class="lightcolor"><input name="drop" type="checkbox"
							id="drop" value="1" />&nbsp;&nbsp;如果表同名则删除原表<br />
							同一数据库安装多个iWeb产品时可改变默认前缀</td>
					</tr>
					<tr>
						<th colspan="3"></th>
					</tr>
					<tr>
						<th colspan="3"></th>
					</tr>
				</table>
				<h3>程序配置</h3>
				<table class="data_set">
					<tr>
						<th colspan="3"></th>
					</tr>
					<tr>
						<td width="14%">网站地址</td>
						<td width="37%"><?php echo "http://{$_SERVER['HTTP_HOST']}/";?> <input
							name="isnsDIR" type="text" class="setup_input"
							value="<?php echo $isnsDIR;?>" style="width: 110px;" /></td>
						<td width="49%" class="lightcolor">一般不用修改，向导自动获取</td>
					</tr>
					<tr>
						<th colspan="3"></th>
					</tr>
					<tr>
						<th colspan="3"></th>
					</tr>
				</table>
				<h3>设置管理员信息</h3>
				<table class="data_set">
					<tr>
						<th colspan="2"></th>
					</tr>
					<tr>
						<td width="14%">管理员账户</td>
						<td width="86%"><input type="text" class="setup_input"
							name="admin" value="admin" /></td>
					</tr>
					<tr>
						<th colspan="2"></th>
					</tr>
					<tr>
						<td>管理员密码</td>
						<td><input type="password" class="setup_input" name="password"
							value="" /></td>
					</tr>
					<tr>
						<th colspan="2"></th>
					</tr>
					<tr>
						<th colspan="2"></th>
					</tr>
				</table>
				<!--<h3>选择默认模块&nbsp;<input type="checkbox" checked="checked" name="" /></h3>!-->
				<div class="agree">
					<input type="hidden" name="step" value="3" /> <input
						hidefocus="true" type="submit" class="button"
						value="提交设置信息，开始创建数据库" />
				</div>