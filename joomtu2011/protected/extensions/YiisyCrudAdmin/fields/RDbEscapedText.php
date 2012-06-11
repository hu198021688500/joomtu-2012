<?php

class RDbEscapedText extends RDbBase
{
    public function __toString()
    {
        return CHtml::encode(parent::__toString());
    }
}
