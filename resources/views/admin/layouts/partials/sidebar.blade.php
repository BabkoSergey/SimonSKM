<section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{Auth::user()->avatar ? Auth::user()->avatar : asset('/img/default-user.png')}}" class="img-circle" alt="{{__('User image')}}">
        </div>
        <div class="pull-left info">
            <p>{{Auth::user()->name}}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> {{__('Online')}}</a>
        </div>
    </div>
    
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">        
        <li class="{{ Request::is('admin/dashboard') || Request::is('admin') ? 'active' : '' }}">
            <a href="{{url('/admin/dashboard')}}">
                <i class="fa fa-dashboard"></i> <span>{{__('Dashboard')}}</span>                
            </a>
        </li>
        
        @if(Auth::user()->hasPermissionTo('show orders'))
        <li class="{{ Request::is('admin/orders*') ? 'active' : '' }}">
            <a href="{{url('/admin/orders')}}">
                <i class="fa fa-shopping-cart"></i> <span>{{__('Orders')}}</span>                
            </a>
        </li>
        @endif
        
        @if(Auth::user()->hasPermissionTo('show autos'))
        <li class="{{ Request::is('admin/cars*') ? 'active' : '' }}">
            <a href="{{url('/admin/cars')}}">
                <i class="fa fa-car"></i> <span>{{__('Cars')}}</span>                
            </a>
        </li>
        @endif
        
        @if(Auth::user()->hasPermissionTo('show services'))
        <li class="{{ Request::is('admin/services*') ? 'active' : '' }}">
            <a href="{{url('/admin/services')}}">
                <i class="fa fa-cubes"></i> <span>{{__('Services')}}</span>                
            </a>
        </li>
        @endif
                
        @if(Auth::user()->hasPermissionTo('show articles'))
            <li class="{{ Request::is('admin/articles*') ? 'active' : '' }}">
                <a href="{{route('articles.index')}}"><i class="fa fa-file-text"></i> {{__('Articles')}}</a>
            </li>
        @endif
        
        @if(Auth::user()->hasPermissionTo('show art_categorys'))
<!--            <li class="{{ Request::is('admin/art_categorys*') ? 'active' : '' }}" >
                <a href="{{route('art_categorys.index')}}"><i class="fa fa-tags"></i> {{__('Categorys')}}</a>
            </li>-->
        @endif
            
        @if(Auth::user()->hasPermissionTo('show trailers'))
        <li class="{{ Request::is('admin/trailers*') ? 'active' : '' }}">
            <a href="{{url('/admin/trailers')}}">
                <i class="fa fa-truck"></i> <span>{{__('Trailers')}}</span>                
            </a>
        </li>
        @endif
        
        @if(Auth::user()->hasPermissionTo('users block'))
        <li class="header text-uppercase">{{__('Users')}}</li>        
        <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
            <a href="{{route('users.index')}}">
                <i class="fa fa-users"></i> <span>{{__('Users')}}</span>                
            </a>
        </li>   
        
            @if(Auth::user()->hasAnyPermission(['show roles', 'show permission']))
                <li class="treeview {{ Request::is('admin/roles*') || Request::is('admin/permissions*')? 'active menu-open' : '' }}">
                    <a href="#">
                        <i class="fa fa-key"></i> <span> {{__('Roles & Permissions')}}</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        @if(Auth::user()->hasPermissionTo('show roles'))
                            <li class="{{ Request::is('admin/roles*') ? 'active' : '' }}">
                                <a href="{{route('roles.index')}}"><i class="fa fa-unlock-alt"></i> {{__('Roles')}}</a>
                            </li>
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('show permission'))
                            <li class="{{ Request::is('admin/permissions*') ? 'active' : '' }}" >
                                <a href="{{route('permissions.index')}}"><i class="fa fa-unlock"></i> {{__('Permissions')}}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
        
        @endif
        
        @if(Auth::user()->hasAnyPermission([ 'setting translate' ]))
        <li class="header text-uppercase">{{__('Settings')}}</li>        
            
            @if(Auth::user()->hasPermissionTo('setting translate'))
                <li class="{{ Request::is('admin/settings/general*') ? 'active' : '' }}">
                    <a href="{{route('general.index')}}">
                        <i class="fa fa-wrench"></i> <span>{{__('Main Settings')}}</span>                
                    </a>
                </li>   
            @endif
            
            @if(Auth::user()->hasPermissionTo('show pages'))
                <li class="{{ Request::is('admin/pages*') ? 'active' : '' }}">
                    <a href="{{route('pages.index')}}"><i class="fa fa-file-text"></i> {{__('Pages')}}</a>
                </li>
            @endif
                
            @if(Auth::user()->hasPermissionTo('setting translate'))
                <li class="{{ Request::is('admin/settings/translate*') ? 'active' : '' }}">
                    <a href="{{route('settings.translate.index')}}">
                        <i class="fa fa-language"></i> <span>{{__('Translations')}}</span>                
                    </a>
                </li>   
            @endif
        
        @endif
                
                
    </ul>
</section>