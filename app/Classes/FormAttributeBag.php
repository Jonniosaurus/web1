<?php
namespace web1\Classes;
/**
 * An object designed to hold other objects for quick access by the Form Builder. 
 * @param  array<iFormAttribute> $attributes 
 */
class FormAttributeBag {
  private $type = '';
  private $attributes = array();
  public function __construct($type, $attributes) {
    $this->type = is_string($type) ? $type : '';
    $this->attributes = is_array($attributes) ? $attributes : array();
  }
  public function getType() {
    return $this->type;    
  }
  public function getAttribute($type) {
    foreach ($this->attributes as $attribute) {
      if (get_class($attribute) === $type)
        return $attribute->get();      
    }
    return null;    
  }
}

