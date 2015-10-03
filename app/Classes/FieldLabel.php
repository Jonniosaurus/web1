<?php namespace web1\Classes;

/**
 * If a Different label from the Db column name is required.
 *
 * @author jonniosaurus
 */
class FieldLabel implements iFormAttribute {
  private $label = '';
  function __construct($label) {    
    $this->label = is_string($label) ? $label : '';    
  }
  function get() {
    return $this->label;    
  }
}

