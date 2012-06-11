<?php
/**
 * 2011-10-24 03:12:22
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

class TestController extends FrontController{
	
	public function actionIndex()
	{
		//Yii::app()->cache->set('joomt_111', 'xxxx', 60);
		echo Yii::app()->cache->get('joomt_111');
		//Yii::app()->cache->deleteValue($key);

		$viewData = array();
		$viewData['dataProvider'] = new CActiveDataProvider('User');
		$viewData['time'] = date("D M j G:i:s T Y");
		$viewData['model'] = new TestForm();
		//$viewData['dataProvider'] = new ModulesDataProvider();
		$this->render('index',$viewData);
	}

	// Uncomment the following methods and override them if needed
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		////echo $this->createUrl('actionName', array('params01'=>'value01', 'params02'=>'value02') );
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
}

/*
 * //要写入PHP文件的数组
$write_array = array(
 
 '1' => 'oneone',
 '2' => 'two',
 '3' => 'three',
 '4' => 'four',
 '5' => 'five',
 '6' => array(1,2,3,4)
);
 
//字符串处理
$string_start   = "<?php\n";
$string_process = var_export($write_array, TRUE);
$string_end     = ";\n?>";
$string         = $string_start.$string_process.$string_end;
 
//开始写入文件
echo file_put_contents('test_array.php', $string);
 */