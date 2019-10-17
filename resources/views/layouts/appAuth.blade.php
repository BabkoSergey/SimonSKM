<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <title>{{ config('app.name', 'Simon SKM') }}</title>            

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">

    </head>
    
    <body>
        <section class="wrapper">
            <div class="coming-soon">            
                @yield('content')
            </div>
        </section>

        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="{{ asset('js/jquery-2.1.1.min.js') }}"></script>
        
    </body>    
    
</html>