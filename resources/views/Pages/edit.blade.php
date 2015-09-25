@extends('master')

@section('content')
<h1>{{ $page->title }}</h1>
<!--  bound such as to have a direct correlation with columns in the Db -->

@foreach($data as $d)
{!! Form::model($d, ['route'=>['page', $page->slug] ,'method'=>'PATCH', 'role'=>'form']) !!}
<div class="form-group">
	<div id="{{  'order_' . $d->id }}" class="col-sm-12"> 
	{!! Form::label('order', 'Order:', ['class'=> 'control-label']) !!}
	{!! Form::text('order', null, ['class' => 'form-control', 'id' =>  'Form_order_' . $d->id ]) !!}
	</div>
	<div id="{{  'content_' . $d->id }}" class="col-sm-12"> 
	{!! Form::label('content', 'Content:', ['class'=> 'control-label ']) !!}
	{!! Form::textarea('content', null, ['class' => 'form-control', 'id' =>  'Form_content_' . $d->id ]) !!}
	</div>
	<div id="{{  'wrapper_id_' . $d->id }}" class="col-sm-12">
	{!! Form::label('wrapper_id', 'Wrapper ID:', ['class'=> 'control-label ']) !!}
	{!! Form::text('wrapper_id',null, ['class'=> 'form-control', 'id' =>  'Form_wrapper_id_' . $d->id ]) !!}
	</div>
	<div id="{{  'wrapper_class_' . $d->id }}" class="col-sm-12">
	{!! Form::label('wrapper_class', 'Wrapper Class:', ['class'=> 'control-label ']) !!}
	{!! Form::text('wrapper_class',null, ['class'=> 'form-control', 'id' =>  'Form_wrapper_class_' . $d->id ]) !!}
	</div>
	  
	<div id="{{  'def_class_' . $d->id }}" class="col-sm-12">
	{!! Form::label('def_id', 'Definition:', ['class'=> 'control-label ']) !!}
	<br />
	{!! Form::select('def_id',$defs, $d->defId,['class'=> 'selectpicker', 'id' =>  'Definition_' . $d->id ]) !!}
	</div>
	
	<div id="{{  'submit_' . $d->id }}">	
	{!! Form::hidden('id',null, ['class'=> 'form-control', 'id' =>  'Form_wrapper_class_' . $d->id ]) !!}	
	{!! Form::submit('Update ' . $d->wrapper_id, ['class'=> 'btn btn-primary']) !!}
	</div>
</div>
{!! Form::close() !!}
@endforeach


@stop