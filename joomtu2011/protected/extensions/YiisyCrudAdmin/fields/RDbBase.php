<?php
/**
 * Base field description object related to any field type.
 */
class RDbBase
{
	protected $_model;
	protected $_field;
	protected $_args = array();

	public function __construct( $model, $field, $args = array() )
	{
		$this->_model = $model;
		$this->_field = $field;
		$this->_args = $args;
	}
	public function __toString()
	{
		return (string)$this->_model->{$this->_field};
	}

	public function getFieldFilter()
	{
		return null;
	}

	public function asGridColumn()
	{
		return array(
			'filter' => $this->getFieldFilter(),
			'name' => $this->_field,
			'value' => '(string)$data->getField("'.$this->_field.'")',
		);

	}

	public function asViewAttribute()
	{
		return array(
			'name' => $this->_field,
			'value' => (string)$this,
		);
	}

	public function asActiveForm($activeForm, $options = array('template' => '{label} {input} {error}','htmlOptions' => array()) )
	{

		$template = array_key_exists('template', $options) ? $options['template'] :'{label} {input} {error}' ;
		$htmlOptions = array_key_exists('htmlOptions',$options) ? $options['htmlOptions'] : array();
		$template = str_replace('{label}', $activeForm->labelEx($this->_model, $this->_field), $template);
		$template = str_replace('{input}', $this->renderInput($activeForm, $htmlOptions), $template);
		$template = str_replace('{error}', $activeForm->error($this->_model, $this->_field), $template);
		return $template;
	}

	protected function renderInput($activeForm,$htmlOptions)
	{
		return $activeForm->textField($this->_model, $this->_field, $htmlOptions);
	}

    public function asSearchForm($activeForm, $options)
    {
        $template = isset($options['template']) ? $options['template'] :'{label} {input}' ;
        $htmlOptions = isset($options['htmlOptions']) ? $options['htmlOptions'] : array();

        $template = str_replace('{label}', $activeForm->label($this->_model, $this->_field), $template);
        $template = str_replace('{input}', $this->renderInput($activeForm,$htmlOptions), $template);
        return $template;
    }
}