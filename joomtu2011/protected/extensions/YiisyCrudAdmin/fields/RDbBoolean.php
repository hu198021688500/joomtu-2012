<?php

/**
 * Usually used for fields stored in database with 0 1 values, where 0 is false and 1 is true.
 *
 * Provide more user friendly filter and displaying for it.
 */
class RDbBoolean extends RDbBase
{

	public function __toString()
	{
		return $this->_model->{$this->_field} ? 'true' : 'false';
	}

	public function getFieldFilter()
	{
		return array('0' => 'false', '1' => 'true');
	}


	protected function renderInput($activeForm,$htmlOptions)
	{
		return $activeForm->checkBox($this->_model, $this->_field,$htmlOptions);
	}
}