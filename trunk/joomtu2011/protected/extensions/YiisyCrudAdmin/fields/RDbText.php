<?php
class RDbText extends RDbBase
{
	protected function renderInput($activeForm,$htmlOptions)
	{
		return Yii::app()->controller->widget('application.extensions.cleditor.ECLEditor', array(
			'model'=>$this->_model,
			'attribute'=>$this->_field, //Model attribute name. Nome do atributo do modelo.
			'options'=>array(
				'width'=>'600',
				'height'=>250,
				'useCSS'=>true,
			),
		),true);
	}

	public function __toString()
	{
		return $this->getLimitedTextWithoutHtml();
	}

	public function getLimitedTextWithoutHtml($limit = 50 )
	{
		$str = ''.parent::__toString();
		$str = strip_tags($str);
		$words = preg_split("/\s+/s", $str);

		if ( count($words) < 2 )
		{
            if ( strlen($str) > $limit )
			    $out =  substr($str, 0, $limit).' ...';
            else
                $out = $str;
		}
		else
		{
			$out = '';
			foreach ( $words as $word )
			{
				$out .= $word. ' ';
				if ( strlen( $out ) > $limit )
                {
                    $out .= ' ... ';
					break;
                }
			}
		}
		return $out;

	}

}