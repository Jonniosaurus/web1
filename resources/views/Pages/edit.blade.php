@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>

@foreach($contents as $content)

<div>
 {!! 
    HTML::link(
      route(
        'page.edit.content', 
        [$page->slug, $content->wrapper_id]),
        '<' . $content->ofType($content->def_id) . ' href="' . $page->slug . '/edit/' . $content->wrapper_id . '" />',
        ['class'=>'editLink', 'title'=>$content->wrapper_id]
    )
  !!}
</div>
@endforeach
{!! 
    HTML::link(
      route(
        'page.create', 
        [$page->slug, $content->id]),
        'New content...',
        ['class'=>'editLink', 'title'=>'new content']
        )
      
!!}
@stop