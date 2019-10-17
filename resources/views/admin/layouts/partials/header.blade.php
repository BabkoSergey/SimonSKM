<header class="main-header">

    <!-- Logo -->
    <a href="{{url('admin')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            <img src="{{asset('/img/logo.png')}}">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Symon</b> SKM</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
                
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">                  
                <li class="dropdown notifications-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{App::getLocale()}}" alt="en" />
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header text-center text-bold">{{__('Avalible languages')}}</li>
                        <li>
                            <!-- inner menu: contains the actual data -->
                            <ul class="menu">
                                <li>
                                    <a href="#" class="jq_lang-set" data-locale="en">
                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-en" alt="en" />
                                        En {{__('English')}}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="jq_lang-set" class="jq_lang-set" data-locale="de">
                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-de" alt="de" />
                                        De {{__('Deutsch')}}
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="jq_lang-set" data-locale="ru">
                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-ru" alt="ru" />
                                        Ru {{__('Russian')}}
                                    </a>
                                </li>                                
                            </ul>
                        </li>
                    </ul>
                </li>
                
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{Auth::user()->avatar ? Auth::user()->avatar : asset('/img/default-user.png')}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{Auth::user()->avatar ? Auth::user()->avatar : asset('/img/default-user.png')}}" class="img-circle" alt="User Image">
                            <p>
                                {{Auth::user()->name}}
                                <small>
                                    @if(!empty(Auth::user()->getRoleNames()))
                                        @foreach(Auth::user()->getRoleNames() as $roleName)
                                            {{ $roleName }}&nbsp;
                                        @endforeach
                                    @endif                                    
                                </small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">                                
                                <a class="btn btn-default btn-flat" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Sign out') }}</a>            
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
<!--                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>-->
            </ul>
        </div>

    </nav>
</header>

@push('scripts')
    <script>
        $(function () {  
           $(document).on('click','.jq_lang-set',function (e){
                e.preventDefault(); 
                setCookie('setLang',$(this).attr('data-locale'));               
                window.location.reload(true);
            });  
                
            
        });
        
    </script>    
@endpush
