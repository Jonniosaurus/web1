<!-- resources/views/auth/login.blade.php -->
@extends('master')

@section('content')
<form method="POST" action="/auth/login">

    <div>
        Email
        {!! Form::email('email', old('email')) !!}        
    </div>

    <div>
        Password
        {!! Form::password('password') !!}        
    </div>

    <div>
        {!! Form::checkbox('remember') !!} Remember Me
    </div>

    <div>
        <button type="submit">Login</button>
    </div>
</form>
@stop