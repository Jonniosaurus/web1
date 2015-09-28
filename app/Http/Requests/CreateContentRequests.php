<?php

namespace web1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateContentRequests extends FormRequest
{  
  public function rules() {
    return 
    [
      'content'      => 'required',
      'order'         => 'required|integer',
      'wrapper_id'    => 'required|unique:contents,wrapper_id',
      'wrapper_class' => 'max:255',
    ]; 
  }
  
  public function authorize() {
    return true;
  }
} 
