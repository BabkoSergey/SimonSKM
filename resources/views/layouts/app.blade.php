<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    @section('htmlheader')
        @include('.layouts.partials.htmlheader')
    @show

    <body>
        <div class="cookie ">
            <div class="wrapper">
                    <span class="attention-cookie">
                        {{ __('!_Wenn Sie Ihren Besuch auf dieser Website fortsetzen, akzeptieren Sie die Verwendung von Cookies zur Erstellung von Besucherstatistiken. Erfahren Sie mehr.') }}
                        <a href="{{url($locale.'/terms')}}">{{ __('!_ALLGEMEINE GESCHÄFTSBEDINGUNGEN UND KUNDENINFORMATIONEN') }}</a>
                        <a href="{{url($locale.'/revocation')}}">{{ __('!_WIDERRUFSRECHT') }}</a>
                    </span>
                    
                    <span class="agree-cookie">{{ __('!_Stimme zu') }}</span>
            </div>
        </div>
        
        <div class='wrapper-page'>
            @section('main_menu')
                @include('.layouts.partials.main_menu')
            @show
            <div class="push">
                @yield('content')
            </div>
        </div>
        
        @hasSection('footer') 
            @include('layouts.partials.footer')
        @endif
        
    </body>
    
    @section('scripts')
        @include('layouts.partials.scripts')
    @show
    
</html>