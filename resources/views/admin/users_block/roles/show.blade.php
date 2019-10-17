@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Roles') }} @endsection

@section('sub_title') {{ __('Show info') }} @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
                
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ $role->name }}</h3>
                <a class="btn btn-default pull-right" role="button" href="{{ route('roles.index') }}"> {{ __('Back to Role List') }}</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">                                                                                                
                        <strong>Permissions:</strong>
                        <br>
                        @if(!empty($rolePermissions))
                            @foreach($rolePermissions as $v)
                                <label class="label label-success">{{ $v->name }}</label>
                            @endforeach
                        @endif
                    </div>
                </div>                
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{ __('Users by role') }} {{ $role->name }}</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">                
                <table id="users-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>                            
                            <th>Roles</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>                            
                            <th>Roles</th>
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

@endsection

@push('styles')    
    <link rel="stylesheet" href="{{ asset('/plugins/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endpush

@push('scripts')    
    <script src="{{ asset('/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/plugins/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    
    <script>
      $(function () {        
        
        $('#users-table').DataTable({
            ajax: "{{url('admin/users_dt_ajax?role=')}}{{ $role->name }}",
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            columns: [
                {   data: 'id' },
                {   data: 'name'},
                {   data: 'email'},
                {   data: 'roles'}
            ]
        });    
          
      });
    </script>
@endpush