<?php

namespace web1\Classes;
use Form;
/**
 * A form builder that accepts a list of forms to generate or - if blank - creates a "create" form.
 * accepted field types: 'text', 'textarea', 'select'
 * @param  Dictionary<string, string> $fields 
 * @param  string $goto (redirect)
 * @param  string $action (GET/POST/PATCH/etc.)
 * @param  string $buttonText ('Create'/'Update'/etc.)
 */
class FormBuilder{    
  private $fields;
  private $goto;
  private $action;  
  private $buttonText;
  public $output = '';
  
  function __construct($fields, $goto, $action, $buttonText) {
    $this->fields = $fields;    
    $this->goto = $goto;
    $this->action = $action;
    $this->buttonText = $buttonText;
  }
  
  private function makeForm($data, &$errors) {    
      $localOutput = '';      
      // 1.) Create new form braces and assign action   and where it will redirect to.
      $localOutput .= 
        Form::open(['route'=>$this->goto, 'method' => $this->action, 'role'=>'form']) .
        $this->errorHandler($errors); // handle error messages
      
      $i = 0;     
      foreach ($this->fields as $fieldKey => $fieldValue) {
        $localOutput .= 
          '<div class="form-group ' . ($errors->has($fieldKey) ? 'has-error' : '') . '">' .        
          // 2.) add a field label         
          $this->setLabel($fieldKey, $fieldValue) .
          // 3.) handle 1d fields (like text fields)           	
          $this->setField($fieldKey, $fieldValue, $data, $i) .
          '</div>';
        $i++;	
      }    
      // 5.) add submission component to form and hidden id field.
	  return
        $localOutput . '<div class="submissionComponent">' .
	    Form::hidden('id',(isset($data) ? $data->id : ''), ['class'=> 'form-control', 'id' =>  'Form_wrapper_class_' . (isset($data) ? $data->id : $i) ]) . 
	    Form::submit($this->buttonText, ['class'=> 'btn btn-primary']) . '</div>' .
        Form::close();       
  }
  
  private function errorHandler(&$errors) {
    $errorHeader = '';
     if (count($errors->all()) > 0) {
        $errorHeader .= '<div class="alert alert-danger"><h4>Validation errors detected:</h4><ul>';
        foreach($errors->all() as $error) {
          $errorHeader .= '<li>' . $error . '</li>';          
        }
        $errorHeader .= '</li></div>';
        
      }    
    return $errorHeader;
  }
  
  private function setLabel($fieldKey, $fieldValue) {
    $label = '';
    if (gettype($fieldValue) != 'array') {
      $label .= Form::label($fieldKey, $fieldKey . ': ', ['class'=> $fieldKey . '-label']);
    } else 
      if(!array_key_exists('hidden',$fieldValue)) {
        $label .= Form::label($fieldKey, $fieldKey . ': ', ['class'=> $fieldKey . '-label']);
    }
    return $label;
  }
  
  private function setField($fieldKey, $fieldValue, $data, $i) {
    $localOutput = '';
    switch($fieldValue){
          case 'text':
            $localOutput .= Form::text($fieldKey,isset($data) ? $data[$fieldKey] : '', ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
            break; 			
          case 'textarea':
            $localOutput .= Form::textarea($fieldKey,isset($data) ? $data[$fieldKey] : '', ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
            break;
          case 'numeric':
            $localOutput .= Form::number($fieldKey,isset($data) ? $data[$fieldKey] : '', ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
            break;
          case 'disabled':
            $localOutput .= Form::text($fieldKey,isset($data) ? $data[$fieldKey] : '', ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i, 'disabled' => 'true' ]);
            break;
          case 'email':
            $localOutput .= Form::email($fieldKey,isset($data) ? $data[$fieldKey] : '', ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
            break;
          case 'password':
            $localOutput .= Form::password('password');
            break;          
      	default: 
      	  // 4.) handle Nd fields (like optionsets).
      	  if(count($fieldValue) > 0) {
      	    foreach ($fieldValue as $optionKey => $optionValue) {
        		switch($optionKey) {   			    	
                  case 'select':				    			
                    $localOutput .= "<br />" . 
                      Form::select(
                        $fieldKey,
                        $optionValue, 
                    	  isset($data) ? $data[$fieldKey] : '',
                    	  ['class'=> 'selectpicker', 'id' =>  $fieldKey . '_' . $i ]);
                    break;
                  case 'hidden':
                    $localOutput .= Form::hidden($fieldKey, $optionValue);
                }  
       	      }
            }
        }
    return $localOutput;
  }
  
  function __call($name, $data) {        
    switch ($name) {
      case 'build':          
        switch (count($data)) {
          case 2:            
            $this->output .= $this->makeForm($data[0], $data[1]);       
            break;
          case 1:
            $this->output .= $this->makeForm(null, $data[0]);
            break;
        }
        
        return $this->output;
    }
  }
}