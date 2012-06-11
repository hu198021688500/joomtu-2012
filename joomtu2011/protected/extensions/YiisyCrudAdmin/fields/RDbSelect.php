<?php

class RDbSelect extends RDbBase
{

    public function getFieldFilter()
    {
        return $this->_args[0];
    }


	protected function renderInput( CActiveForm $activeForm, $htmlOptions = array())
	{
		return $activeForm->dropDownList($this->_model, $this->_args[1], $this->_args[0], $htmlOptions);
	}

}
