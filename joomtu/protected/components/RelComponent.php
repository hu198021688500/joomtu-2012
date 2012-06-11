<?php

/**
 * 关系组件
 * 2012-3-8 04:21:31
 * @package protected.components
 * @version 1.0
 *
 * @author hub <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */
class RelComponent {

    public function __construct() {
        
    }

    public static function getRelStr($uid, $toUid = null) {
        $relModel = new UserRel();
        if ($toUid === null) {
            if (!Yii::app()->user->id) {
                return null;
            } else {
                $toUid = Yii::app()->user->id;
            }
        }
        return $relModel->getPathByQuery($uid, $toUid);
    }

}