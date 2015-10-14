<!-- resources/views/auth/login.blade.php -->
@extends('master')

@section('content')
<h1>Login</h1>
{!! $forms->build($errors) !!}
@stop