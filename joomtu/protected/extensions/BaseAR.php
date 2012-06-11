<?php
/**
 * 2012-3-7 11:03:16
 * @package protected.extensions
 * @version 1.0
 *
 * @author hadoop <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */

class BaseAR extends CActiveRecord
{
	public function useDb($db = null)
    {    
        self::$db = Yii::app()->$db;
        return $this;
    }
    
    public function onBeforeSave($event)
	{
		$this->db = Yii::app()->masterDb;
	}

	public function onAfterSave($event)
	{
		$this->db = Yii::app()->db;
	}
    
    //$cmd = Yii::app()->filedb->createCommand($sql);
}