@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Pages') }} @endsection

@section('sub_title') {{ __('List') }} @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @include('admin.templates.action_notifi')
        
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ __('Pages') }}</h3>

                <div class="box-tools">
                    @if(Auth::user()->hasPermissionTo('add pages'))
                        <a class="btn btn-success margin-r-10 margin-t-5 pull-right" role="button" href="{{ route('pages.create') }}"> {{ __('Add New Page') }}</a>
                    @endif
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table">
                    <tbody>
                        <tr>
                            <th style="width: 10px">#ID</th>
                            <th>{{ __('URL')}}</th>
                            <th>{{ __('Name')}} || {{ __('Title')}}</th>
                            <th>{{ __('Content')}}</th>
                            <th style="width: 150px">{{ __('Action') }}</th>
                        </tr>
                        
                        @foreach($pages as $page)
                            <tr>
                                <td>{{$page->id}}</td>
                                <td>{{$page->url}}</td>
                                <td>{{$page->content->name ?? $page->content->title}}</td>
                                <td>{{$page->content->content}}</td>
                                <td>
                                    @if(Auth::user()->hasPermissionTo('delete pages'))
                                        <form id="jq_service-delete-form" method="POST" action="{{ url('/admin/pages/') }}/{{$page->id}}" accept-charset="UTF-8" style="    display: inline-block;">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">    
                                            <button class="btn btn-danger" type="submit" value="Delete"><i class="fa fa-trash"></i></button>
                                        </form>                                            
                                    @endif

                                    @if(Auth::user()->hasPermissionTo('edit pages'))
                                        <a href="{{ url('admin/pages') }}/{{$page->id}}/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                    @endif

                                    @if(Auth::user()->hasPermissionTo('show pages'))
                                        <a href="{{ url('admin/pages') }}/{{$page->id}}" class="btn btn-success"><i class="fa fa-eye"></i></a>
                                    @endif
                                    
                                </td>
                            </tr>
                        @endforeach
                        
                       
                    </tbody></table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@if(Auth::user()->hasPermissionTo('delete pages'))
<div style="display: none">
    <form id="jq_service-delete-form" method="POST" action="" data-url="{{ url('/admin/pages/') }}" accept-charset="UTF-8">
        @csrf
        <input name="_method" type="hidden" value="DELETE">    
        <input class="btn btn-danger" type="submit" value="Delete">
    </form>
</div>
@endif

@endsection

@push('styles')
    
@endpush

@push('scripts')    
        
    <script>
      $(function () {  
                  
      });
    </script>
@endpush