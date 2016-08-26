<?php

namespace web1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSampleRequests extends FormRequest{
    public function rules() {
    return 
    [
      'title'      => 'required',
      'slug'       => 'required',      
    ]; 
  }
  
  public function authorize() {
    return true;
  }
}
