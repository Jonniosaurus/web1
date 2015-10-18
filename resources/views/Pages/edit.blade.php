@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>

@foreach($contents as $content)

<p>
 {!! 
    HTML::link(
      route(
        'page.edit.content', 
        [$page->slug, $content->wrapper_id]),
        '- '. $content->order . ' *** ' . $content->ofType($content->def_id) . ' *** ' . $content->wrapper_id . ' -',
        ['class'=>'editLink', 'title'=>$content->wrapper_id]
    )
  !!}
</p>
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