<?php

/**
 * 2011-10-27 16:09:02
 * @package protected.components
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011 http://www.test.com
 * @license (http://www.apache.org/licenses/LICENSE-2.0)
 *
 * @$Id$
 * 
 */
class CommonComponent {

    public function setWidth($value) {
        $this->_width = $value;
    }

    public function getWidth() {
        return $this->_width;
    }

    public function onClicked($event) {
        $this->raiseEvent('onClicked', $event);
    }

}