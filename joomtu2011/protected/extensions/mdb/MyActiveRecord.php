<?php
class MyActiveRecord extends CActiveRecord {

	public $dbCom;
	
	public function selectDb($comName = null){
		if (is_null($comName)){
			$this->dbCom = 'db';
		}else{
			$this->dbCom = $comName;
		}
		return $this;
	}
	
    public function getDbConnection(){
    	self::$db = Yii::app()->getComponent($this->dbCom);
    	if(self::$db instanceof CDbConnection)
    		return self::$db;
    	else
    		throw new CDbException(Yii::t('yii','Active Record requires a "db2" CDbConnection application component.'));
    }
}
?>