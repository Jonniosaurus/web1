<?php namespace web1\Classes;

/**
 * If a specific FieldValue is required other than the norm
 *
 * @author jonniosaurus
 */
class FieldValue implements iFormAttribute{
  private $value;
  function __construct($value) {
    $this->value = $value;
  }
  
  function get() {
    return $this->value;    
  }  
}
