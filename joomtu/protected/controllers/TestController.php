<?php

/**
 * 2012-3-28 11:42:42 UTF-8
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

/**
 * Description of TestController
 *
 * @author hugb
 */
class TestController extends Controller {

    public function actionIndex() {
        $area = new AreaExt();
        //$area->moveNode(3, 517, 18);
        //$area->removeNode(3);
        echo $area->analyze();
        echo $area->dump(true);
    }

    public function actionTest() {
        var_dump(array_keys(array()));
        $area = new AreaExt();
        var_dump($area->dump(true));
    }

    public function actionXX() {
        $area = new AreaExt();
        //echo $area->reset();
        //var_dump($area->dump(true));
        //die();2800
        $area->reset();
        $sql = 'select * from {{areas1}} order by area_id';
        $re = Yii::app()->db->createCommand($sql)->queryAll();
        $pid = 1;
        $order = 0;
        foreach ($re as $value) {
            if ($value['area_type'] == 0) {
                continue;
            }
            if ($value['parent_id'] == $pid) {
                $order++;
            } else {
                $order = 1;
                $pid = $value['parent_id'];
            }
            $area->createArea($value['parent_id'], $order, array('name' => $value['area_name']));
        }
        echo $area->analyze();
    }

}

?>
