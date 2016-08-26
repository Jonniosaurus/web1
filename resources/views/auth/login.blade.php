<!-- resources/views/auth/login.blade.php -->
@extends('master')

@section('content')
<h1>Login</h1>
dd($forms);
{!! $forms->build($errors) !!}
@stop