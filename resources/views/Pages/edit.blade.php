@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>
{{ $errors->has('contents') }}
{{ $errors->has('order') }}
{{ $errors->has('wrapper_class') }}
{{ $errors->has('wrapper_id') }}
@foreach($contents as $content) 
<div>
 {!! 
    HTML::link(
      route(
        'page.edit.content', 
        [$page->slug, $content]),
        '<a href="' . $page->slug . '/' . $content . '">',
        ['class'=>'menuItem', 'title'=>$content]
    )
  !!}
</div>
@endforeach
@stop