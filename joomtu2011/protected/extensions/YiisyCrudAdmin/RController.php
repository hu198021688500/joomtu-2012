<?php
/**
 * Created by JetBrains PhpStorm.
 * User: cd
 * Date: 3/29/11
 * Time: 9:38 PM
 * To change this template use File | Settings | File Templates.
 */

Yii::import('ext.YiisyCrudAdmin.RModelAdmin');

class RController extends Controller
{
	public $actionAdminTemplate = 'ext.YiisyCrudAdmin.views.admin';
	public $actionViewTemplate = 'ext.YiisyCrudAdmin.views.view';
	public $actionUpdateTemplate = 'ext.YiisyCrudAdmin.views.update';
	public $formTemplate = 'ext.YiisyCrudAdmin.views._form';
	public $actionCreateTemplate = 'ext.YiisyCrudAdmin.views.create';
	public $searchTemplate = 'ext.YiisyCrudAdmin.views._search';

    protected function setAdminMessage($msg)
    {
        Yii::app()->user->setFlash('admin_message', $msg);
    }

    protected function getAdminMessage()
    {
        if ( Yii::app()->user->hasFlash('admin_message') )
            return Yii::app()->user->getFlash('admin_message');
        else
            return '';
    }
	
	/**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

    protected $_modelName = '';

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
        );
    }

    /**
     * Displays a particular model.
     */
    public function actionView()
    {
	    $model = $this->loadModel();
	    $this->render($this->actionViewTemplate,array(
            'model'=> $model,
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model=new $this->_modelName;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST[ $this->_modelName]))
        {
            $model->attributes=$_POST[ $this->_modelName];
            if($model->save())
                $this->redirect(array('view','id'=>$model->getPrimaryKey()));
        }

        $this->render($this->actionCreateTemplate,array(
            'model'=>$model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     */
    public function actionUpdate()
    {
        $model=$this->loadModel();

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST[ $this->_modelName ]))
        {
            $model->attributes=$_POST[ $this->_modelName];
            if($model->save())
                $this->redirect(array('view','id'=>$model->getPrimaryKey()));
        }

        $this->render($this->actionUpdateTemplate,array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     */
    public function actionDelete()
    {
        if(Yii::app()->request->isPostRequest)
        {
            // we only allow deletion via POST request
            $this->loadModel()->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->redirect(array('admin'));
        }
        else
            throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
    }



    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new $this->_modelName('search');

        $model->unsetAttributes();  // clear any default values
        if(isset($_GET[$this->_modelName]))
            $model->attributes=$_GET[ $this->_modelName ];

        $this->render($this->actionAdminTemplate,array(
            'model'=>$model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     */
    public function loadModel()
    {
        if($this->_model===null)
        {
            $inst = new $this->_modelName();
            if(isset($_GET['id']))
                $this->_model=$inst->model()->findbyPk($_GET['id']);
            if($this->_model===null)
                throw new CHttpException(404,'The requested page does not exist.');
        }
        return $this->_model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']=== strtolower($this->_modelName).'-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
