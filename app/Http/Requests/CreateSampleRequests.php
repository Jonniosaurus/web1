<?php

namespace web1\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSampleRequests extends FormRequest{
    public function rules() {
    return 
    [
      'title'      => 'required',
      'slug'       => 'required',
      'type_id'    => 'required|integer'
    ]; 
  }
  
  public function authorize() {
    return true;
  }
}
