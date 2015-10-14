<?php

namespace web1\Classes;
/**
 * Add a custom field to the class
 *
 * @author jonniosaurus
 */
class FieldClass implements iFormAttribute {
  private $class = '';
  function __construct($label) {    
    $this->class = is_string($label) ? $label : '';    
  }
  //put your code here
  function get() {
    return $this->class;    
  }
}
