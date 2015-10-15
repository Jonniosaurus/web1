<?php

namespace web1\Classes;
use Form;
use FormAttributeBag;
/**
 * A form builder that accepts a list of fields to generate or - if blank - creates a blank form.  
 * @param  Iterative Array<string $name, string $type || FormAttributeBag $bag> $fields
 * @param  string $goto             - Named Route to redirect to.
 * @param  string $action           - GET/POST/PATCH/etc.
 * @param  string $buttonText       - 'Create'/'Update'/etc.
 */
class FormBuilder{    
  private $fields;
  private $goto;
  private $action;  
  private $buttonText;
  private $output = '';
  
  function __construct($fields, $goto, $action, $buttonText) {
    $this->fields = $fields;    
    $this->goto = $goto;
    $this->action = $action;
    $this->buttonText = $buttonText;
  }
  
  // helper
  private function hasAttribute($attribBag, $attrib) {      
    return (!is_string($attribBag) 
      && (get_class($attribBag) === __NAMESPACE__ . '\\FormAttributeBag')) 
        ? $attribBag->getAttribute(__NAMESPACE__ . '\\' . $attrib)
        : null;    
  }
  
  private function parseFieldValue($fieldKey, $fieldValue, $data) {     
    return // confirm a value to populate (where applicable)
      isset($data) && !$this->hasAttribute($fieldValue, 'FieldOptionset') // has a specific dataset been passed in?
        ? $data[$fieldKey] 
        // has a specific field value been attributed?
        : (($value = $this->hasAttribute($fieldValue, 'FieldValue'))
          ? $value
          // is the specified field a select field?
          : (($value = $this->hasAttribute($fieldValue, 'FieldOptionset'))
            ? $value
            : ''
            )
          );        
  }
  
  // if the fieldValue is a string, treat it as the field's type.
  // else, check for a field type in the FormAttributeBag
  private function parseFieldType($fieldValue) {
    return is_string($fieldValue) 
      ? $fieldValue 
      : (get_class($fieldValue) === __NAMESPACE__ . '\\FormAttributeBag' 
        ? $fieldValue->getType() 
        : null);     
  }
  
  private function buildForm($data, &$errors) {
   
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
          $this->setField($fieldKey, $fieldValue, $data) .
          '</div>';
        $i++;	
      }    
      // 4.) add submission component to form and hidden id field.
	  return
        $localOutput . 
        '<div class="submissionComponent">' .	         
	    Form::submit($this->buttonText, ['class'=> 'btn btn-primary']) . 
        '</div>' .
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
    $fieldLabel = // has a field label been manually defined
      ($fieldLabel =  $this->hasAttribute($fieldValue, 'FieldLabel'))
      ? $fieldLabel
      : (($this->parseFieldType($fieldValue) != 'hidden')         
        ? ucfirst($fieldKey)
        : '');
    
    return $fieldLabel != ''
      ? Form::label($fieldKey, $fieldLabel . ': ', ['class'=> $fieldKey . '-label'])        
      : '';
  }
  
  private function setField($fieldKey, $fieldValue, $data) {
    $localOutput = '';    
    // confirm what type of field we are rendering.
    $fieldType = $this->parseFieldType($fieldValue);
    
    // does form have a custom class?
    $customClass = $this->hasAttribute($fieldValue, 'FieldClass');
    
    $fieldClass = [
      'class'=> 'form-control' . ($customClass ? ' ' . $customClass : ''), 
      'id' =>  'Form_' . $fieldKey];
    
    // confirm a value to populate (where applicable)
    $fieldValue = $this->parseFieldValue($fieldKey, $fieldValue, $data);               
      switch($fieldType){
        case 'text':            
          $localOutput .= Form::text(
            $fieldKey,
            $fieldValue, 
            $fieldClass);
          break; 			
        case 'textarea':          
          $localOutput .= Form::textarea(
            $fieldKey,
            $fieldValue, 
            $fieldClass);
          break;
        case 'numeric':
          $localOutput .= Form::number(
            $fieldKey,
            $fieldValue, 
            $fieldClass);
          break;
        case 'email':
          $localOutput .= Form::email(
            $fieldKey,
            $fieldValue,
            $fieldClass);
        break;      	 
        case 'disabled':
          $formClass['disabled'] = 'true';
          $localOutput .= Form::text(
            $fieldKey,
            $fieldValue,
            $fieldClass);
          break;
        case 'password':
          $localOutput .= Form::password($fieldKey, $fieldClass);
          break;          
        case 'select':
          $localOutput .= "<br />" . 
          Form::select(
            $fieldKey,
            $fieldValue,
            isset($data) ? $data[$fieldKey] : '',
            ['class'=> 'dropdown', 'id' =>  'form_' . $fieldKey ]);
          
          break;
        case 'hidden':
          $localOutput .= Form::hidden(
            $fieldKey, 
            $fieldValue);
          break;
        case 'checkbox':
          $localOutput .= Form::checkbox(
            $fieldKey,
            $fieldValue );
          break;             	                    
      }         	    
      
    return $localOutput;
  }
  
  // essentially method overriding a-la PHP
  function __call($name, $data) {      
    
    switch ($name) {
      case 'build':          
        switch (count($data)) {
          case 2:                      
            $this->output .= $this->buildForm($data[0], $data[1]);       
            break;
          case 1:
            $this->output .= $this->buildForm(null, $data[0]);
            break;
        }
        
        return $this->output;
    }
  }
}