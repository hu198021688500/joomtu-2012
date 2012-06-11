<?php
$this->breadcrumbs=array(
	'Default'=>array('/install/default'),
	'StepThree',
);?>
<?php
$creatable = 0;
if(trim($_POST['dbname']) == "" || trim($_POST['dbhost']) == "" || trim($_POST['dbuser']) == "" ){
?>
<p>请返回并确认所有选项均已填写.</p>
<hr size="1" noshade="noshade" />
<p align="right">
	<input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
</p>
<?php
	} elseif(!@mysql_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpw'])) {
?>
<p>数据库不能连接.</p>
<hr size="1" noshade="noshade" />
<p align="right">
	<input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
</p>
<?php
	} elseif(!@mysql_select_db($_POST['dbname'])&&!isset($_POST['create'])) {
?>
<p>
数据库
<?php echo $_POST['dbname'];?>不存在.
</p>
<hr size="1" noshade="noshade" />
<p align="right">
	<input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
</p>
<?php
	} elseif(strstr($_POST['tablepre'], '.')) {
?>
<p>您指定的数据表前缀包含点字符，请返回修改.</p>
<hr size="1" noshade="noshade" />
<p align="right">
	<input class="formbutton" type="button" value="上一步" onclick="history.back(1)" />
</p>
<?php
	} else {}?>
		
<textarea name="notice" readonly="readonly" rows="10" cols="86" id="notice"></textarea>
<div class="agree">
	<input type="hidden" name="step" value="4" /> <input
		hidefocus="true" type="submit" id="createTables" class="button"
		style="color: #b6b6b6; cursor: wait;" disabled="disabled"
		value="正在创建数据库...请稍后" />
</div>