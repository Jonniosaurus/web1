@extends('master')

@section('content')

@foreach ($pages as $page)
  @if ($page->title != 'home')  
  	<div class="menuItemwrapper">
  		{!! 
  			HTML::link(
  				route('page', [$page->slug]), 
  				$page->title, 
  				['id'=>str_replace(" ", "_", $page->title), 'class'=>'menuItem']) 
  		!!}
  		<br />
  		{!!
  		  		HTML::link(
  				route('page.edit', [$page->slug]), 
  				$page->title . ' edit', 
  				['id'=>str_replace(" ", "_", $page->title), 'class'=>'menuItem'])
  		!!} 
  	</div>
  @endif
@endforeach

@stop