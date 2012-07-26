<?php

/**
 * 2011-12-20 11:13:42
 * @package protected.controllers
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 *
 */
class RelController extends Controller {

    public $relBehavior;

    public function init() {
        parent::init();
        $this->relBehavior = new UserRel();
    }

    public function actionMy() {
        $result = $this->relBehavior->getNode(5);
        var_dump($result);
    }

    public function actionTest() {
        $xx = $this->relBehavior->addUserRel($this->relBehavior->rels[2], 1, 2);
        var_dump($xx);
        //var_dump($relModel->traverse(1,2));
        //$relModel->test1();
    }

    /**
     *
     */
    public function actionIndex() {
        $rel = new UserRel();
        $rel->getPathByQuery(5, 1);
        $viewData = array();
        $viewData['users'] = $this->relBehavior->getRecommendUserByEmail('');
        $viewData['relTypes'] = $this->relBehavior->rels;
        $this->render('index', $viewData);
    }

    /**
     *
     */
    public function actionAdd() {
        $uid = Yii::app()->getRequest()->getParam('id');
        $relType = Yii::app()->getRequest()->getParam('type');
        $result = $this->relBehavior->addUserRel($this->relBehavior->rels[$relType], $uid);
        if ($result) {
            echo json_encode(array('status' => true, 'msg' => '添加成功'));
        } else {
            echo json_encode(array('status' => false, 'msg' => '添加失败'));
        }
    }

    public function actionMyRel() {
        Yii::app()->clientScript->registerScriptFile(Yii::app()->params['siteUrl'] . 'scripts/arbor.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->params['siteUrl'] . 'scripts/arbor-tween.js');
        Yii::app()->clientScript->registerScriptFile(Yii::app()->params['siteUrl'] . 'scripts/myrel.js');
        $this->render('myrel');
    }

}