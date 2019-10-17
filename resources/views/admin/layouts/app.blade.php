<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    @section('htmlheader')
        @include('.admin.layouts.partials.htmlheader')
    @show

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">

            @section('header')
                @include('.admin.layouts.partials.header')
            @show

            <aside class="main-sidebar">
                @section('sidebar')
                    @include('.admin.layouts.partials.sidebar')
                @show
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <section class="content-header">
                    @section('content_header')
                        @include('.admin.layouts.partials.content_header')
                    @show
                </section>    

                <section class="content">

                    @yield('content')
                </section>

            </div>

            @section('footer')
                @include('admin.layouts.partials.footer')
            @show  

            @section('control_sidebar')
                @include('admin.layouts.partials.control_sidebar')
            @show  

        </div>

        @section('scripts')
            @include('admin.layouts.partials.scripts')
        @show

    </body>

</html>