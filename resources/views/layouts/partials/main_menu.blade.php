@hasSection('top_light') 
    <div class="header push" style="background :  rgba(19, 19, 19, 0)">
@else
    <div class="header push" style="background :  rgba(19, 19, 19, 1)">
@endif
    <div class='wrapper'>
        <div class="header-logo">
            <a href="{{url($locale)}}">
                <img src="{{ asset($general['logo'] ?? 'assets/logo.png')}}" alt="" />
            </a>
        </div>
        <div class="header-content">
            <a href="tel:{{isset($general['phones'][0]) ? str_replace(' ', '', $general['phones'][0]) : ''}}" class="header-content-phone">
                <img src="{{ asset('assets/phone-yell.svg')}}" alt="" />
                <div class="content-phone-list">
                    <span>{{$general['phones'][0] ?? ''}}</span>
                </div>                
            </a>
            <ul class="header-content-locale">                
                @foreach($langs as $avalLocale)                                        
                    @if($locale == $avalLocale)                        
                        <li class="header-content-locale-item capitalize-text selected">{{$avalLocale}}</li>                         
                    @else                        
                        <li class="header-content-locale-item"><a class="capitalize-text" href="{{$urls[$avalLocale]}}">{{$avalLocale}}</a></li>                        
                    @endif                              
                @endforeach                
            </ul>

            <a href="#menu" class="menu-link">&#9776;</a>
            <nav id="menu" class="panel " role="navigation">
                <div class="close-menu">  <img src="{{ asset('assets/close.svg')}}" alt="" id='close'></div>         
                <ul>
                    <a id="main" class="menu-item" href="{{url($locale)}}">{{ __('!_menu_main') }}</a>
                    <a id="caravan_rent" class="menu-item" href="{{url($locale.'/caravan_rent')}}">{{ __('!_menu_caravan rent') }}</a>
                    <a id="cars" class="menu-item" href="{{url($locale.'/cars')}}">{{ __('!_menu_car sale') }}</a>
                    <a id="services" class="menu-item" href="{{url($locale.'/services')}}">{{ __('!_menu_services') }}</a>
                    <a id="blog" class="menu-item" href="{{url($locale.'/blog')}}">{{ __('!_menu_blog') }}</a>
                    <a id="contacts" class="menu-item" href="{{url($locale.'/contacts')}}">{{ __('!_menu_contacts') }}</a>
                </ul>
            </nav>
            
        </div>
    </div>
</div>