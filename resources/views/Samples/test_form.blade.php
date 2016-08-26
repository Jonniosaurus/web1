@extends('master')

@section('content')
<h1>Dynamic Form Sample</h1>
<p>This is a simple sample of a form generated using my FormBuilder() function.</p>
<div class="form-group">{!! $test->build($errors) !!}</div>
<div class='bottomSpace'>
  {!! HTML::link(
        route('page', 'examples'),
          'back to Examples',
          ['title'=>'back to Examples']
      ) 
  !!}
</div>
<br />
@stop