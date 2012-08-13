<?php

/**
 * 2012-7-16 11:40:36 UTF-8
 * @package
 * @version 1.0
 *
 * @author hugb <hu198021688500@163.com>
 * @copyright (c) 2011-2015
 * @license ()
 *
 * $Id$
 *
 */

/**
 * 统一的错误处理
 */
class ErrorBehavior {

    private $__errors;

    public function __construct() {
        $this->errors = array();
        $this->warnings = array();
        $this->notice = array();
        $this->infos = array();
        $this->debugs = array();
    }

    public function addError($value, $return = false) {
        $this->__errors[] = $value;
        return $return;
    }

    public function setErrors($value) {
        $this->__errors = $value;
    }

    public function getErrors() {
        return $this->__errors;
    }

}