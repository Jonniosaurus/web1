@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>

<div class="form-group">{!! $forms->build($content, $errors) !!}</div>
<div>
  {!! HTML::link(
        route(
          'page.edit', 
          [$page->slug]),
          'back to ' . $page->slug . '/edit',
          ['class'=>'menuItem', 'title'=>$content]
    ) 
  !!}
</div>
@stop