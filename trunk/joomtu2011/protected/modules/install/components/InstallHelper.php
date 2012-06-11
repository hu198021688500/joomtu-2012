<?php 
class InstallHelper {
	
	/**
	 * 检测系统是否被安装
	 * Enter description here ...
	 */
	public static function isInstall(){
		$modulePath = YiiBase::getPathOfAlias('install');
		$lockFile = $modulePath.'data/install.lock';
		return file_exists($lockfile);
	}
	
	/**
	 * 防止 PHP 5.1.x 使用时间函数报错
	 * Enter description here ...
	 */
	public static function correctTimeFun(){
		function_exists('date_default_timezone_set') && date_default_timezone_set('Etc/GMT+0');
		unset($_ENV,$HTTP_ENV_VARS,$_REQUEST,$HTTP_POST_VARS,$HTTP_GET_VARS,$HTTP_POST_FILES,$HTTP_COOKIE_VARS,$HTTP_SESSION_VARS,$HTTP_SERVER_VARS);
		unset($GLOBALS['_ENV'],$GLOBALS['HTTP_ENV_VARS'],$GLOBALS['_REQUEST'],$GLOBALS['HTTP_POST_VARS'],$GLOBALS['HTTP_GET_VARS'],$GLOBALS['HTTP_POST_FILES'],$GLOBALS['HTTP_COOKIE_VARS'],$GLOBALS['HTTP_SESSION_VARS'],$GLOBALS['HTTP_SERVER_VARS']);
		if (ini_get('register_globals')){
			isset($_REQUEST['GLOBALS']) && die('发现试图覆盖 GLOBALS 的操作');
			// Variables that shouldn't be unset
			$noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE','_SERVER', '_ENV', '_FILES');
			$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
			foreach ( $input as $k => $v ){
				if ( !in_array($k, $noUnset) && isset($GLOBALS[$k]) ) {
					$GLOBALS[$k] = NULL;
					unset($GLOBALS[$k]);
				}
			}
		}
	}
	
	/**
	 * Fix for IIS, which doesn't set REQUEST_URI
	 * Enter description here ...
	 */
	public static function correctIIS(){
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			// IIS Mod-Rewrite
			if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
				$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
			}
			// IIS Isapi_Rewrite
			else if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
				$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
			}else{
				// Some IIS + PHP configurations puts the script-name in the path-info (No need to append it twice)
				if ( $_SERVER['PATH_INFO'] == $_SERVER['SCRIPT_NAME'] )
				$_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
				else
				$_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . $_SERVER['PATH_INFO'];
	
				// Append the query string if it exists and isn't null
				if (isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
					$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
				}
			}
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public static function correctPHP(){
		// Fix for PHP as CGI hosts that set SCRIPT_FILENAME to something ending in php.cgi for all requests
		if ( isset($_SERVER['SCRIPT_FILENAME']) && ( strpos($_SERVER['SCRIPT_FILENAME'], 'php.cgi') == strlen($_SERVER['SCRIPT_FILENAME']) - 7 ) )
		$_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED'];

		// Fix for Dreamhost and other PHP as CGI hosts
		if (strpos($_SERVER['SCRIPT_NAME'], 'php.cgi') !== false)
		unset($_SERVER['PATH_INFO']);
		
		// Fix empty PHP_SELF
		$PHP_SELF = $_SERVER['PHP_SELF'];
		if ( empty($PHP_SELF) ){
			$_SERVER['PHP_SELF'] = $PHP_SELF = preg_replace("/(\?.*)?$/",'',$_SERVER["REQUEST_URI"]);
		}
	}
	
	/**
	 * 检测php扩展
	 * Enter description here ...
	 */
	public static function checkPhpExt(){
		if ( version_compare( '5.0', phpversion(), '>' ) ) {
			die( '您的服务器运行的 PHP 版本是' . phpversion() . ' 但 iweb_sns 要求至少 5.0。' );
		}
	
		if ( !extension_loaded('mysql')){
			die( '您的 PHP 安装看起来缺少 MySQL 数据库部分，这对 iweb_sns 来说是必须的。' );
		}
	
		if ( !function_exists("gd_info")) {
			die( '您的服务器没有开启GD库' );
		}
	}
	
	public static function dd(){
		require_once(ROOT_PATH.'install/common.php');
		if ( get_magic_quotes_gpc() ) {
			$_GET    = stripslashes_deep($_GET);
			$_POST   = stripslashes_deep($_POST);
			$_COOKIE = stripslashes_deep($_COOKIE);
		}
		$_GET    = add_magic_quotes($_GET);
		$_POST   = add_magic_quotes($_POST);
		$_COOKIE = add_magic_quotes($_COOKIE);
		$_SERVER = add_magic_quotes($_SERVER);
	
		!$_SERVER['PHP_SELF'] && $_SERVER['PHP_SELF']=$_SERVER['SCRIPT_NAME'];
		$isnsDIR=preg_replace(array('/^\//','/(install)$/'),'',dirname($_SERVER['PHP_SELF']));
		$step = isset($_POST['step']) ? $_POST['step'] : '1';
	}
	
	public static function dirPermCheck(){
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
	}
}
?>