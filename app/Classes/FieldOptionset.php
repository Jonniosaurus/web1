<?php namespace web1\Classes;

/**
 * a Key => Value array of optionsets for a select field
 *
 * @author jonniosaurus
 */
class FieldOptionset implements iFormAttribute{
  private $options = array();
  function __construct($options) {
    $this->options = is_array($options) ? $options : array();    
  }
  function get() {
    return $this->options;    
  }
}