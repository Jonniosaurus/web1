@extends('master')

@section('content')
<h1>Create Sample Page</h1>

<div class="form-group">{!! $create->build($errors) !!}</div>
<div>
  {!! HTML::link(
        route('samples'),
          'back to Samples',
          ['title'=>'back to Samples']
      ) 
  !!}
</div>
@stop