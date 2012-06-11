<?php
/**
 * Short desc
 * 
 * Created by JetBrains PhpStorm.
 * @author zadoev@gmail.com
 * @version 29.03.11 15:28
 * @package ${END}
 */
 

/**
 * Base model admin class. All admin classes provide description about how to display model, model fields in admin.
 *
 * If you need custom admin for model named Xxxx you need create
 *
 * <code>
 * class XxxxAdmin extends RModelAdmin
 * {
 * //... override methods you need
 * }
 * </code>
 *
 * Most popular minimalistic admin looks like
 *
 * <code>
 * class RUserAdmin extends RModelAdmin
 * {
 *      protected $_adminName = 'user'; // One item name, used in breadcrums, in header in actionAdmin
 *      protected $_pluralName = 'users';// Item set name
 *      protected $_repr = 'login';// method or property used to retrieve model string represantation. It's close to __toString but only for admin
 *
 *      public function getFormFields()// list of fields to show in form, also order for them
 *      {
 *                 return array(
 *                         'login',
 *                         'email',
 *                         'active',
 *                         'blocked',
 *                         'is_admin',
 *                 );
 *      }
 *      public function getAdminFields()//list of fields to show in actionAdmin also order for them
 * 		{
 * 				return array(
 * 						'login',
 * 						'email',
 * 						'active',
 * 						'blocked',
 * 						'is_admin',
 * 				);
 * 		}
 * }
 * </code>
 */
class RModelAdmin extends CComponent
{
	protected $_model;
	protected $_adminName = '';
	protected $_pluralName = '';

	/**
	 * Property name used to show model object as string ( for user it can be 'login', it can be complex, for example
	 * <code>
	 *
	 * class User extends RActiveRecord
	 * {
	 *      public function getFullName()
	 *      {
	 *          return $this->first_name . ' ' . $this->last_name;
	 *      }
	 * }
	 *
	 * class UserAdmin extends RModelAdmin
	 * {
	 *      protected $_repr = 'fullName';
	 * }
	 * </code> 
	 *
	 *
	 * @var string
	 */
	protected $_repr = '';


	public function __construct( $model )
	{
		$this->_model = $model;
	}

	public function getReprName()
	{
		return $this->_repr;
	}

	/**
	 * Name of entity used to display in admin. If $_adminName not set, try to get information from model.  
	 *
	 * @return string
	 */
	public function getAdminName()
	{
		if ( $this->_adminName )
			$name = $this->_adminName;
		else
			$name = get_class($this->_model);
        $module = Yii::app()->getController()->getModule();
        if ( $module )
		    return  Yii::t(ucfirst($module->id).'Module.translation',$name);
        else
            return $name; 
	}


	/**
	 * Name of entity set used to display in admin. If $_pluralName not set trying to get information from $_adminName
	 *
	 * @return string
	 */
	public function getPluralName()
	{
		if ( $this->_pluralName )
			$adminName =  $this->_pluralName;
		else
		{
			$adminName = $this->getAdminName();
			if ( $adminName{strlen($adminName) - 1 } != 's' )
				$adminName .= 's';
		}

        $module = Yii::app()->getController()->getModule();
        if ( $module )
		    return  Yii::t(ucfirst($module->id).'Module.translation',$adminName);
        else
            return $adminName;

	}

	/**
	 * Returns list of fields to be excluded from html form (update, create)
	 *
	 * @return array
	 */
	public function getFormExcludedFields()
	{
		return array();
	}

	/**
	 * Returns list of fields to be showed in html (update,created). Fields described in {@link getFormExcludedFields()} will be excluded from this list
	 *
	 * @return array
	 */
	public function filterFormFields()
	{
		$out = array();
		$excludedFields = $this->getFormExcludedFields();

		if ( is_string($excludedFields ) )
			$excludedFields = array($excludedFields);
		
		foreach ( $this->getFormFields() as $field)
		{
			if ( ! in_array($field, $excludedFields))
				$out[] = $field;
		}

		return $out;
	}

	/**
	 * Returns columns for CGridView widget
	 *
	 * @param array $last last column, for example CButtonColumn. Do not pass if no need. 
	 * @return array
	 */
	public function getGridColumns( $last = array())
	{
		$columns = array();

		$excludeFields = $this->getAdminExcludedFields();
		if ( is_string($excludeFields ) )
			$excludeFields = array($excludeFields);
		
		foreach ( $this->getAdminFields() as $field )
		{
			if ( in_array($field, $excludeFields ) )
				continue;
			if ( is_array($field) )
			{
				$columns[] = $field;
				continue;
			}

			$columns[] = $this->_model->getField($field)->asGridColumn();
		}
		if ( $last )
			$columns[] = $last;

		return $columns;
	}

	/**
	 * Returns list of attributes for CDetailView widget. Fields in {@link getViewExcludedFields()} will be excluded.
	 *
	 * @return array
	 */
	public function getViewAttributes()
	{
		$attributes = array();

		$excludeAttributes = $this->getViewExcludedFields();

		if ( is_string($excludeAttributes) )
			$excludeAttributes = array($excludeAttributes);

		foreach ( $this->getViewFields() as $field )
		{
			if ( in_array($field, $excludeAttributes ) )
				continue;
			if ( is_array($field) )
			{
				$attributes[] = $field;
				continue;
			}

			$attributes[] = $this->_model->getField($field)->asViewAttribute();
		}
		return $attributes;

	}

	/**
	 * Return breadcrumbs data for actionAdmin 
	 *
	 * @return array for breadcrumbs
	 */
	public function getAdminBreadcrumbs()
	{
		return  array(
			ucfirst($this->getPluralName()) =>array('index'),
			'All',
		);
	}

	/**
	 * Returns breadcrumbs data for actionView 
	 *
	 * @return array
	 */
	public function getViewBreadcrumbs()
	{
		return array(
			ucfirst($this->getPluralName())=>array('index'),
			Yii::t("YiisyCrudAdmin",'View') .'  #  '.$this->getRepr(),
		);
	}

	/**
	 * Returns breadcrumbs data for actionUpdate
	 *
	 * @return array
	 */
	public function getUpdateBreadcrumbs()
	{
		return array(
			ucfirst($this->getPluralName())=>array('index'),
			$this->getRepr()=>array('view','id'=>$this->_model->getPrimaryKey()),
			Yii::t("YiisyCrudAdmin",'Update'),
		);
	}

	/**
	 * Returns breadcrumbs data for actionCreate
	 *
	 * @return array
	 */
	public function getCreateBreadcrumbs()
	{
		return array(
			ucfirst($this->getPluralName())=>array('index'),
			Yii::t('YiisyCrudAdmin', 'Create'),
		);
	}

	/**
	 * Returns menu data for actionCreate 
	 *
	 * @return array
	 */
	public function getCreateMenu()
	{
		return array(
			array('label'=> Yii::t("YiisyCrudAdmin", 'Manage').' ' . $this->getPluralName(), 'url'=>array('admin')),
		);
	}



	/**
	 * Returns menu data for actionUpdate
	 * @return array
	 */
	public function getUpdateMenu()
	{
		$menu =  array(
			array('label'=>Yii::t('YiisyCrudAdmin', 'Create').' ' .$this->getAdminName(), 'url'=>array('create')),
			array('label'=>Yii::t('YiisyCrudAdmin', 'View').' '.$this->getRepr(), 'url'=>array('view', 'id'=>$this->_model->getPrimaryKey())),
			array('label'=>Yii::t('YiisyCrudAdmin', 'Manage'). ' '.$this->getPluralName(), 'url'=>array('admin')),
		);
		$additionalMenu = $this->getExtendedUpdateMenu();

		if ( $additionalMenu )
			return array_merge($menu, $additionalMenu);
		return
			$menu;
	}

	/**
	 * Returns text for header in actionCreate 
	 *
	 * @return string
	 */
	public function getCreateHeader()
	{
		return Yii::t('YiisyCrudAdmin', 'Create').' ' . $this->getAdminName();
	}

	/**
	 * Return text for header in actionUpdate
	 * @return string
	 */
	public function getUpdateHeader()
	{
		return 'Update #'.$this->getRepr();		
	}

	/**
	 * Returns text for header in actionView
	 *
	 * @return string
	 */
	public function getViewHeader()
	{
		$name = $this->getRepr();

		return 'View  #  '.$name;
	}

	/**
	 * Returns object string representation for admin.
	 * @return
	 */
	public function getRepr()
	{
		return $this->_repr ? $this->_model->{$this->_repr} : $this->_model->getPrimaryKey();
	}

	/**
	 * Returns menu items for actionView. Appending menu items with data from {@link getExtendedViewMenu()}
	 *
	 * @return array
	 */
	public function getViewMenu()
	{
		$menu = array(
			array('label'=>Yii::t('YiisyCrudAdmin', 'Create').' '.$this->getAdminName(), 'url'=>array('create')),
			array('label'=>Yii::t("YiisyCrudAdmin",'Update').' '.$this->getRepr(), 'url'=>array('update', 'id'=>$this->_model->getPrimaryKey())),
			array('label'=>Yii::t("YiisyCrudAdmin",'Delete').' ' .$this->getRepr(), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$this->_model->getPrimaryKey()),'confirm'=>'Are you sure you want to delete this item?')),
			array('label'=>Yii::t("YiisyCrudAdmin", 'Manage').' ' .strtolower($this->getPluralName()), 'url'=>array('admin')),
		);
		$additionalMenu = $this->getExtendedViewMenu();

		if ( $additionalMenu )
			return array_merge($menu, $additionalMenu);
		return
			$menu;

	}

	/**
	 * Returns menu items which will be appended to default  menu in actionView
	 *
	 * @return array
	 */
	public function getExtendedViewMenu()
	{
		return array();
	}

	/**
	 * Returns menu items which will be appended to default menu in actionUpdate
	 *
	 * @return array
	 */
	public function getExtendedUpdateMenu()
	{
		return array();
	}

	/**
	 * Returns text for header in actionAdmin
	 *
	 * @return string
	 */
	public function getAdminHeader()
	{
		return ucfirst($this->getPluralName());
	}

	/**
	 * Returns menu items for actionAdmin
	 *
	 * @return array
	 */
	public function getAdminMenu()
	{
		return array(
			array('label'=>Yii::t('YiisyCrudAdmin', 'Create') . ' ' .strtolower($this->getAdminName()), 'url'=>array('create')),
		);
	
	}

	/**
	 * Returns list of fields will be excluded in actionView 
	 *
	 * @return array
	 */
	public function getViewExcludedFields()
	{
		return array();
	}

	public function getAdminExcludedFields()
	{
		return array();
	}

	public function getViewFields()
	{
		return array_keys($this->_model->getAttributes());
	}

    public function getSearchFields()
    {
        return array_keys($this->_model->getAttributes());
    }

	public function getAdminFields()
	{
		return array_keys($this->_model->getAttributes());
	}

	public function getTitle()
	{
		throw new Exception("Deprecated method ".__METHOD__);
		return Yii::t("YiisyCrudAdmin", 'Manage').' '  . $this->_model->tableName();
	}

    public function getFormFields()
    {
        return array_keys($this->_model->getAttributes());        
    }

    public function getFieldsDescription()
    {
        return array();
    }

}