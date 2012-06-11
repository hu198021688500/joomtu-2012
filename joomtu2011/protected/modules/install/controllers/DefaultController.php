<?php

class DefaultController extends Controller
{
	public $layout = 'main';
	
	function actionIndex(){
		$this->render('index');
	}
	
	public function actionStepOne(){
		$this->render('StepOne');
	}
	
	public function actionStepFour()
	{
		$this->render('StepFour');
	}

	public function actionStepThree()
	{
		$this->render('StepThree');
	}

	public function actionStepTwo()
	{
		$this->render('StepTwo');
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
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
	*/
}