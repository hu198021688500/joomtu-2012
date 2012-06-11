<?php
$this->breadcrumbs=array(
	'Default'=>array('/install/default'),
	'StepFour',
);?>
<div class="tips">
	<p>
		<strong>
			<ul>
				<li>恭喜，iWebSNS安装程序已经顺利执行完毕！</li>
				<li>为了您的数据安全，请尽快删除整个 install 目录</li>
			</ul>
		</strong>
	</p>
</div>
<br />
<div class="data_create">
<?php
	echo "正在生成文件检验镜像……<br/>";
	if(scan_file_make_md5($webRoot,array('php','js','html'))){
		echo "文件检验镜像已生成。<br/>";
	}else{
		echo "文件检验镜像未能成功生成，您可以进入管理后台手动生成。<br/>";
	}
	list_child_file("default");
	file_put_contents(ROOT_PATH.'./docs/install.lock',"");
?>
</div>
<div class="agree">
	<div class="btn">
		<div class="btn_right">
			<a href="../">进入首页</a>
		</div>
	</div>
	<div class="btn">
		<div class="btn_right">
			<a href="../sysadmin/login.php">直接进入管理后台</a>
		</div>
	</div>
</div>