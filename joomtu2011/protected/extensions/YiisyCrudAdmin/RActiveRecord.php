<?php
/**
 * Short desc
 * 
 * Created by JetBrains PhpStorm.
 * @author zadoev@gmail.com
 * @version 29.03.11 14:19
 * @package ${END}
 */

Yii::import('ext.YiisyCrudAdmin.fields.*');

class RFieldsManager
{
	public function __construct( $model )
	{
		$fields = $model->getAdmin()->getFieldsDescription();
		foreach ( array_diff( array_keys($model->getAttributes()) , array_keys($fields)  ) as $field)
		{
			$this->_fields[$field] = new RDbBase( $model, $field);
		}

		foreach ( $fields as $field => $class )
		{
			$args = array();
			if ( is_array($class) )
			{
				$args = $class;
				$class = array_shift($args);
			}

			$this->_fields[$field ] = new $class( $model, $field, $args);
		}
	}

	public function getField($fieldName)
	{
		return $this->_fields[$fieldName];
	}
}


class RActiveRecord extends CActiveRecord
{
	protected $_fieldsManager = null;

	public function getAdmin()
	{
		$adminClassName = get_class($this).'Admin';

		if ( ! class_exists( $adminClassName,false ) )
        {
            var_dump('not exists', $adminClassName);
			return new RModelAdmin($this);
        }
		return new $adminClassName($this);
	}

	public function getField( $fieldName )
	{
		return $this->getFieldsManager()->getField($fieldName);
	}

	public function getFieldsManager()
	{
		if ( $this->_fieldsManager == null )
			$this->_fieldsManager = new RFieldsManager($this);
		return $this->_fieldsManager;
	}
}