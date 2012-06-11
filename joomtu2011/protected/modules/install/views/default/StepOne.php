<?php
$this->breadcrumbs=array(
	'Default'=>array('/install/default'),
	'StepOne',
);?>
<?php 
	$check=1;
	$no_write=$isnsDIR."程序根目录无法书写,请速将根目录属性设置为0777";
	$correct='<font style="color:green;">√</font>';
	$incorrect='<font style="color:red;">× 0777属性检测不通过</font>';
	$uncorrect='<font style="color:red;">× 文件不存在请上传此文件</font>';
	$w_check=array(
	1=>array('path'=>'uploadfiles', 'competence'=>'读/写/删', 'explain'=>'文件上传目录', 'result'=>''),
	2=>array('path'=>'plugins', 'competence'=>'读/写/删', 'explain'=>'插件目录', 'result'=>''),
	3=>array('path'=>'skin', 'competence'=>'读/写/删', 'explain'=>'皮肤目录', 'result'=>''),
	4=>array('path'=>'templates', 'competence'=>'读/写/删', 'explain'=>'模板目录', 'result'=>''),
	5=>array('path'=>'models', 'competence'=>'读/写/删', 'explain'=>'模块程序目录', 'result'=>''),
	6=>array('path'=>'modules', 'competence'=>'读/写/删', 'explain'=>'程序执行目录', 'result'=>''),
	7=>array('path'=>'uiparts', 'competence'=>'读/写/删', 'explain'=>'程序段目录', 'result'=>''),
	8=>array('path'=>'modules.php', 'competence'=>'读/写', 'explain'=>'显示容器', 'result'=>''),
	9=>array('path'=>'do.php', 'competence'=>'读/写', 'explain'=>'执行容器', 'result'=>''),
	10=>array('path'=>'configuration.php', 'competence'=>'读/写', 'explain'=>'配置文件', 'result'=>''),
	11=>array('path'=>'docs/version.txt', 'competence'=>'读/写', 'explain'=>'版本信息', 'result'=>''),
	12=>array('path'=>'sysadmin/toolsBox', 'competence'=>'读/写/删', 'explain'=>'系统工具', 'result'=>''),
	13=>array('path'=>'main.php', 'competence'=>'读/写/删', 'explain'=>'main页面', 'result'=>''),
	14=>array('path'=>'home.php', 'competence'=>'读/写/删', 'explain'=>'home页面', 'result'=>''),
	15=>array('path'=>'index.php', 'competence'=>'读/写/删', 'explain'=>'index页面', 'result'=>''),
	16=>array('path'=>'foundation/fdelay.php', 'competence'=>'读/写/删', 'explain'=>'延迟刷新', 'result'=>''),
	17=>array('path'=>'iweb_mini_lib/conf/dbconf.php', 'competence'=>'读/写/删', 'explain'=>'数据库配置', 'result'=>''),
	18=>array('path'=>'docs', 'competence'=>'读/写/删', 'explain'=>'安装文件', 'result'=>''),
	19=>array('path'=>'docs/bak', 'competence'=>'读/写/删', 'explain'=>'升级备份目录', 'result'=>''),
	19=>array('path'=>'sysadmin/temp', 'competence'=>'读/写/删', 'explain'=>'临时文件目录', 'result'=>''),
	19=>array('path'=>'sysadmin/md5_file', 'competence'=>'读/写/删', 'explain'=>'文件检验镜像目录', 'result'=>''),
	);
	if($fp=@fopen(ROOT_PATH.'test.txt',"w+")){
		$state=$correct;
		fclose($fp);
	} else{
		$state=$incorrect.$no_write;
		$check=0;
	}

	foreach($w_check AS $key=>$val){
		if(!file_exists(ROOT_PATH.$val['path'])){
			$w_check[$key]['result'] = $uncorrect;$check=0;
		}else {
			if (is_dir(ROOT_PATH.$val['path'])){
				//这里只校验一级目录
				$check_dir = scandir(ROOT_PATH.$val['path']);
				if (!empty($check_dir[2])){ //非空文件夹
					foreach ($check_dir as $v){
						if(!is_writable(ROOT_PATH.$val['path'].'/'.$v)){
							$w_check[$key]['result'] =$incorrect;
							$check=0;
							break;
						}
					}
					if ($w_check[$key]['result'] !=$incorrect){
						$w_check[$key]['result'] = $correct;
					}
				} else {
					if($fp=@fopen(ROOT_PATH.$val['path'].'/test.txt',"w+")){
						$w_check[$key]['result'] = $correct;
						fclose($fp);
						@unlink(ROOT_PATH.$val['path'].'/test.txt');
					} else{
						$w_check[$key]['result'] =$incorrect;
						$check=0;
					}
				}
			} else {
				if(is_writable(ROOT_PATH.$val['path'])){
					$w_check[$key]['result'] = $correct;
				} else{
					$w_check[$key]['result'] =$incorrect; $check=0;
				}
			}

		}
	}
	$check && @unlink(ROOT_PATH.'test.txt');
	?>
<div class="tips">
	<p>
		<strong>夺彩互联网，创新IT动力</strong>
	</p>
</div>
<table class="list" width="100%">
	<tr>
		<th>名称</th>
		<th>所需权限属性</th>
		<th>说明</th>
		<th>检测结果</th>
	</tr>
	<?php
	foreach($w_check as $key=>$val){
		echo '<tr><td>'.$val['path'].'</td><td>'.$val['competence'].'</td><td>'.$val['explain'].'</td><td>'.$val['result'].'</td></tr>';
	}
	?>
</table>
<div class="clear"></div>
<div class="agree">
	<input type="hidden" name="step" value="2" />
	<?php if($check){?>
	<input hidefocus="true" type="submit" class="button"
		value="接受授权协议，开始安装" />
		<?php }else{?>
	<input hidefocus="true" type="button" disabled class="button"
		value="你的安装条件不符合规范" />
		<?php }?>
	<span>请先认真阅读我们的<a href="javascript:void(0);">《软件使用授权协议》</a> </span>

</div>
preg_match_all("|(CREATE TABLE (.*?));|i",$sql, $create); //取出create语句
preg_match_all("|(INSERT INTO (.*?)\));|i",$sql, $insert); //取出insert语句
$drop = empty($_POST['drop'])? 0:1; sqlQuery($set[1],0,$tablePreStr);
sqlQuery($drop[1],$drop,$tablePreStr); $tablenum =
sqlQuery($create[1],$drop,$tablePreStr);
sqlQuery($insert[1],0,$tablePreStr); return $tablenum; } //模板编译 function
list_child_file($local){ $compile_type="serve";
$ref=opendir("../templates/".$local); while($tp_dir=readdir($ref)){
if(!preg_match("/^\./",$tp_dir)){
if(filetype("../templates/".$local."/".$tp_dir)=="dir"){
list_child_file($local."/".$tp_dir); }
if(filetype("../templates/".$local."/".$tp_dir)=="file"){
$loc='default'; $show_local=$local.'/'.$tp_dir;
$show_local=preg_replace("/$loc\//","",$show_local);
tpl_engine($loc,$show_local,0,$compile_type); } } } } function
create_table($table_info){ echo '
<script type="text/javascript">showMsg(\''.addslashes($table_info).' \');</script>
'."\r\n"; flush(); } ?>
