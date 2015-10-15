@extends('master')

@section('content')
<h1>{{ $sample->title }}</h1>
<div class="form-group">{!! $edit->build($sample, $errors) !!}</div>
<div class="form-group">{!! $delete->build($errors) !!}</div>

<div>
  {!! HTML::link(
        route('samples'),
          'back to ' . $sample->slug . '/edit',
          ['title'=>'back to ' . $sample->slug . '/edit']
      ) 
  !!}
</div>
@stop