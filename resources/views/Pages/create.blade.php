@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>

<div class="form-group">{!! $forms->build($errors) !!}</div>
@stop