@extends('master')

@section('content')
<h1>Samples</h1>

@foreach($samples as $sample)

<div>
  <ul>
    <li>
    {!! 
        HTML::link(
          route(
            'samples.edit', 
            [$sample->slug]),
            $sample->title,
            ['class'=>'editLink', 'title'=>'edit' . $sample->title]
        )
     !!}
    </li>
  </ul>
</div>
@endforeach
{!! 
    HTML::link(
      route('samples.create'),
        'New content...',
        ['class'=>'editLink', 'title'=>'new content']
      )      
!!}
@stop