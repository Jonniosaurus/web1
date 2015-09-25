@extends('master')

@section('content')

<ul>
<h1>{{ $page->title }}</h1>
@foreach ($contents as $content)
  @if ($content->title != 'home')  
  	<div class="{{ $content->wrapperClass }}" id="{{ $content->wrapperId }}">
  		{!!	nl2br($content->content) !!}
  	</div>
  @endif
@endforeach
</ul>
@stop
