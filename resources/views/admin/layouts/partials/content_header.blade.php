<h1>
    @hasSection('htmlheader_title') @yield('htmlheader_title') @else {{__('Page')}} @endif
    
    @hasSection('sub_title') <small>@yield('sub_title')</small> @endif
    
    @hasSection('content_title_add') @yield('content_title_add') @endif
    
</h1>

@hasSection('breadcrumb') 
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
@endif
