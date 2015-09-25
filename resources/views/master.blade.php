<!DOCTYPE html>
<html>
    <head>
        <title>JonnyEdwards.net</title>        
		
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  		<link rel="stylesheet" href="/css/dist/css/bootstrap-select.css">
  		<script src="/css/dist/js/bootstrap-select.js"></script>
        <style>
        /*
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
            */
        </style>
    </head>
    <body>
        <div class="container">
        	@yield('header')
            <div class="content">
            	@if (Session::has('success'))
            	<div class="alert alert-success">
            		{{ session::get('success') }}
            	</div>
            	@endif
            	@yield('content')                            	    
            </div>
            @if(Route::getCurrentRoute()->getName() != 'home')
            {!! link_to_route('home', 'home page') !!}
            @endif            
        </div>
        
    </body>
</html>
