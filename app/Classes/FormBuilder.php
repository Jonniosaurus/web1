<?php

namespace web1\Classes;
use Form;
/**
 * Show the form for editing the specified resource.
 *
 * @param  int  $
 * @param  int  $id
 * @param  int  $id
 */
class FormBuilder{  
  
  private $fields;
  private $dataset;
  private $goto;
  private $action;
  
  public $output = '';
  function __construct($fields, $dataset, $goto, $action) {
    foreach($dataset as $data) {
      // 1.) Create new form braces and assign action and where it will redirect to.
      $this->output .= Form::open(['route'=>$goto, 'method' => $action, 'role'=>'form']);
      $i = 0;      
      foreach ($fields as $fieldKey => $fieldValue) {
      	// 2.) add a field label
      	$this->output .= Form::label($fieldKey, $fieldKey . ': ', ['class'=> $fieldKey . '-label']);
      	// 3.) handle 1d fields (like text fields)
        switch($fieldValue){
          case 'text':
            $this->output .= Form::text($fieldKey,$data[$fieldKey], ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
            break; 			
          case 'textarea':
            $this->output .= Form::textarea($fieldKey,$data[$fieldKey], ['class'=> 'form-control', 'id' =>  'Form_' . $fieldKey . $i ]);
            break;
        	default: 
        	  // 4.) handle Nd fields (like optionsets).
        	  if(count($fieldValue) > 0) {
        	    foreach ($fieldValue as $optionKey => $optionValue) {
          		  switch($optionKey) {   			    	
                    case 'select':				    			
                      $this->output .= "<br />" . 
                        Form::select(
                          $fieldKey,
                          $optionValue, 
                      	  $data[$fieldKey],
                      	  ['class'=> 'selectpicker', 'id' =>  $fieldKey . '_' . $i ]);
                      break;
                  }  
        	    }
              }
        }
        $i++;	
      }    
      // 5.) add submission component to form and hidden id field.
	  $this->output .= '<div class="submissionComponent">' .
	    Form::hidden('id',$data->id, ['class'=> 'form-control', 'id' =>  'Form_wrapper_class_' . $data->id ]) . 
	    Form::submit('Update ' . $data->wrapper_id, ['class'=> 'btn btn-primary']) . '</div>' .
        Form::close(); 
        
    }
  }	
  	
  
  
  function build() {
  	//return $this->debug;					
  	return $this->output;
  }
}