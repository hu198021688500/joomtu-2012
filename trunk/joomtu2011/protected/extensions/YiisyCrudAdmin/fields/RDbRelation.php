<?php
class RDbRelation extends RDbBase
{

	protected function getRelatedModel()
	{
		$relName = $this->_args[0];//get relation name

		$relations = $this->_model->relations();//get all relations in current model

		if ( ! isset($relations[$relName]))
			throw new CHttpException(500, "Can not find relation $relName");

		$relInfo = $relations[$relName];// get relation info for current relation

		$relModelName = $relInfo[1];//??? not sure it's good @todo: DANGER, FIXME, need good way to retrive relation mode

		return call_user_func($relModelName.'::model');//related MOdelName::model()

	}

	public function getFieldFilter()
	{
		$model = $this->getRelatedModel();

		$pk = $model->getTableSchema()->primaryKey ;//get primary key info

		if ( is_array($pk) )
			throw new CHttpException("Composed primary key in related record not supported");//@todo: make nice

		return CHtml::listData($model->findAll(),$pk,'admin.repr');

	}

	protected function renderInput($activeForm, $htmlOptions)
	{
		return $activeForm->dropDownList($this->_model, $this->_field, $this->getFieldFilter());
	}

	public function __toString()
	{
		try
		{
			$relName = $this->_args[0];

			if ( $this->_model->$relName === null )
				return "{{ unknown id: ".$this->_model->{$this->_field}."}}";

			return $this->_model->$relName->admin->getRepr();
		}
		catch ( Exception $e)
		{
			return "RAdmin error: ".$e->getMessage();
		}
	}
}