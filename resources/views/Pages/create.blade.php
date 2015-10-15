@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>

<div class="form-group">{!! $forms->build($errors) !!}</div>
  {!! HTML::link(
        route(
          'page.edit', 
          [$page->slug]),
          'back to ' . $page->slug . '/edit',
          ['title'=>'back to ' . $page->slug . '/edit']
    ) 
  !!}
@stop