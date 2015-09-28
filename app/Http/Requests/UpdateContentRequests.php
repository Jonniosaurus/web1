<?php

namespace web1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContentRequests extends FormRequest
{  
  public function rules() {
    return 
    [
      'content'      => 'required',
      'order'         => 'required|integer',
      'wrapper_class' => 'max:255',
    ]; 
  }
  
  public function authorize() {
    return true;
  }
} 
