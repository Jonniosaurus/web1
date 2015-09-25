@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>
<!--  bound such as to have a direct correlation with columns in the Db -->

@foreach($data as $d)
{!! Form::model($d, ['route'=>['page', $page->slug] ,'method'=>'PATCH', 'role'=>'form']) !!}
<div class="form-group">
	@foreach($fields as $field)				
		{-- Form::label($value, $value . ': ', ['class'=> $value . '-label']) --}
		{!! $field !!}
	@endforeach
	<div id="{{  'submit_' . $d->id }}">	
	{!! Form::hidden('id',null, ['class'=> 'form-control', 'id' =>  'Form_wrapper_class_' . $d->id ]) !!}	
	{!! Form::submit('Update ' . $d->wrapper_id, ['class'=> 'btn btn-primary']) !!}
	</div>
</div>
{!! Form::close() !!}
@endforeach


@stop