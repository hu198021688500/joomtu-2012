<?php
class RDbDate extends RDbBase
{
	protected function renderInput($activeForm, $htmlOptions)
	{
		return Yii::app()->controller->widget(
			'zii.widgets.jui.CJuiDatePicker',
			array(
				'model' => $this->_model,
				'attribute' => $this->_field,
				'options' => array(
					'dateFormat'=>'yy-mm-dd',
					'changeMonth' => 'true',
					'changeYear' => 'true',
					'showButtonPanel' => 'true',
					'constrainInput' => 'false'
				)
		    ),
			true
		);
	}
}