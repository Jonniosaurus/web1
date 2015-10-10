@extends('master')

@section('content')

  {!!  HTML::image(
        route('home') . '/images/cartoons/404.svg',
        404,
        ['class' => 'cartoon'])  !!}
      <div class="paragraph"> Sorry, that page can't be found!</div>
@stop