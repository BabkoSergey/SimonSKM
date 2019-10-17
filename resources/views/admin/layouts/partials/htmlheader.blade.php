<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  
  
  <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
        
    <title>
        @hasSection('htmlheader_title') @yield('htmlheader_title') - @endif {{ config('app.name', 'SymonSKM') }}
    </title>

    @section('styles')                
        @include('admin.layouts.partials.styles')
    @show
  
</head>