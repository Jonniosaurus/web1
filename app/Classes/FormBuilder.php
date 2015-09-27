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
  
  private function makeForm($data) {
    $localOutput = '';
      // 1.) Create new form braces and assign action   and where it will redirect to.
      $localOutput .= Form::open(['route'=>$this->goto, 'method' => $this->action, 'role'=>'form']);
      $i = 0;     
      foreach ($this->fields as $fieldKey => $fieldValue) {
      	// 2.) add a field label
      	$localOutput .= Form::label($fieldKey, $fieldKey . ': ', ['class'=> $fieldKey . '-label']);
      	// 3.) handle 1d fields (like text fields)           	
        switch($fieldValue){
          case 'text':
            $localOutput .= Form::text($fieldKey,isset($data) ? $data[$fieldKey] : '', ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
            break; 			
          case 'textarea':
            $localOutput .= Form::textarea($fieldKey,isset($data) ? $data[$fieldKey] : '', ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
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
        $i++;	
      }    
      // 5.) add submission component to form and hidden id field.
	  return
        $localOutput . '<div class="submissionComponent">' .
	    Form::hidden('id',(isset($data) ? $data->id : ''), ['class'=> 'form-control', 'id' =>  'Form_wrapper_class_' . (isset($data) ? $data->id : $i) ]) . 
	    Form::submit($this->buttonText, ['class'=> 'btn btn-primary']) . '</div>' .
        Form::close();       
  }
  
  function __call($name, $dataset) {        
    switch ($name) {
      case 'build':          
        if (count($dataset) > 0) {   
          foreach($dataset[0] as $data) {
            $this->output .= $this->makeForm($data);                      
          }  
        } else {
          $this->output .= $this->makeForm(null);
        }
        return $this->output;
    }
  }
}