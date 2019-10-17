@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Permissions') }} @endsection

@section('sub_title') {{ __('List') }} @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @if($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> {{__('Success')}}!</h4>
                {{ $message }}   
            </div>
        @endif
        
        <div class="box">
            <div class="box-header">
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="permissions-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Permission</th>
                            <th>Guard</th>
                            <th style="width: 90px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Permission</th>
                            <th>Guard</th>
                            <th style="width: 90px;">Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@if(Auth::user()->hasPermissionTo('delete permission'))
<div style="display: none">
    <form id="jq_permission-delete-form" method="POST" action="" data-url="{{ url('/admin/permissions/') }}" accept-charset="UTF-8">
        @csrf
        <input name="_method" type="hidden" value="DELETE">    
        <input class="btn btn-danger" type="submit" value="Delete">
    </form>
</div>
@endif

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@push('scripts')    
    <script src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    
    <script>
      $(function () {        
        
        @if(Auth::user()->hasPermissionTo('add permission'))
            $("#permissions-table").one("preInit.dt", function () {

                $button = $('<a class="btn btn-success margin-l-10" role="button" href="{{ route('permissions.create') }}"> {{ __('Add New Permission') }}</a>');
                $("#permissions-table_filter label").append($button);
                $button.button();

            });                                    
        @endif
        
        $('#permissions-table').DataTable({
            ajax: "{{ url('admin/permissions_dt_ajax') }}",
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            columns: [
                {   data: 'id' },
                {   data: 'name'},
                {   data: 'guard_name'},
                {   data: 'actions',
                    orderable: false,
                    render: function ( data, type, row ) {
                        var actions = '';
                                                
                        @if(Auth::user()->hasPermissionTo('edit permission'))
//                        actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
//                        actions += '<a href="{{ url('admin/permission') }}/'+row.id+'/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
//                        actions += '</div>';
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('delete permission'))
                        actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                        actions += '<a href="" class="btn btn-danger jq_permission-delete" data-jq_permission="'+row.id+'"><i class="fa fa-trash"></i></a>';
                        actions += '</div>';
                        @endif
                        
                        return actions;
                    }
                }
            ]
        });    
        
        $(document).on('click','.jq_permission-delete',function (e){
            e.preventDefault(); 
            $('#jq_permission-delete-form').attr('action',$('#jq_permission-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_permission'));
            $('#jq_permission-delete-form').submit();
        }); 
          
      });
    </script>
@endpush