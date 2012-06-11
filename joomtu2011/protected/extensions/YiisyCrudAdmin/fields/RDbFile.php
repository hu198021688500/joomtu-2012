<?php
class RDbFile extends RDbBase
{
	protected function renderInput($activeForm, $htmlOptions)
	{
		return $activeForm->fileField($this->_model, $this->_field);
	}

}
