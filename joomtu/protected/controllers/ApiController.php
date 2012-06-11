<?php
/**
 * 2011-10-24 03:10:32
 * @package package_name
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */

class ApiController extends FrontController{
	
	public function actionIndex(){
		class Example1 {
		    static function foo() {
		        return 'foo';
		    }
		    function bar() {
		        return 'bar';
		    }
		}
		$server = new PHPRPC_Server();
		$server->add('foo', 'Example1');
		$server->add('bar', new Example1());
		
		$server->setCharset('UTF-8');
		$server->setDebugMode(true);
		$server->setEnableGZIP(true);

		$server->start();
	}
}