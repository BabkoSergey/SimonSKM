@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Services') }} @endsection

@section('sub_title') {{ __('List') }} @endsection

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        @include('admin.templates.action_notifi')
        
        <div class="box">
            <div class="box-header">
                
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="services-table" class="table table-bordered table-striped tabel-mass-actions">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="services-select-all"></th>
                            <th>{{__('Service name')}}</th>
                            <th>{{__('Logo')}}</th>
                            <th>{{__('Content')}}</th>                             
                            <th>{{__('Right')}}</th>                                                         
                            <th>{{__('Status')}}</th>
                            <th style="min-width: 175px;">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>{{__('Service name')}}</th>
                            <th>{{__('Logo')}}</th>
                            <th>{{__('Content')}}</th>                             
                            <th>{{__('Right')}}</th>                                                         
                            <th>{{__('Status')}}</th>
                            <th>{{__('Actions')}}</th>
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

@if(Auth::user()->hasPermissionTo('delete services'))
<div style="display: none">
    <form id="jq_service-delete-form" method="POST" action="" data-url="{{ url('/admin/services/') }}" accept-charset="UTF-8">
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
          
        @if(Auth::user()->hasPermissionTo('add services'))
            $("#services-table").one("preInit.dt", function () {

                $button = $('<a class="btn btn-success margin-l-10" role="button" href="{{ route('services.create') }}"> {{ __('Add New Service') }}</a>');
                $("#services-table_filter label").append($button);
                $button.button();

            });                                    
        @endif
        
        @if(Auth::user()->hasAnyPermission(['delete services', 'edit services']))
            $("#services-table").one("preInit.dt", function () {
                var buttonDelete = buttonHold = buttonActive = '';
                
                @if(Auth::user()->hasPermissionTo('delete services'))
                    buttonDelete = '<a class="btn btn-danger margin-r-10 jq_mass" data-action="delete" role="button" href=""><i class="fa fa-trash"></i></a>';
                @endif
                
                @if(Auth::user()->hasPermissionTo('edit services'))
                    buttonHold = '<a class="btn btn-warning margin-r-10 jq_mass" data-action="hold" role="button" href=""><i class="fa fa-ban"></i></a>';
                    buttonActive = '<a class="btn btn-info margin-r-10 jq_mass" data-action="activate" role="button" href=""><i class="fa fa-asterisk"></i></a>';
                @endif
                
                $("#services-table_filter label").prepend('<div class="dt-mass-block">'+buttonActive+buttonHold+buttonDelete+'</div>');
                $button.button();
            }); 
        @endif
        
        var servicesTable = $('#services-table').DataTable({
            ajax: "{{ url('admin/services_dt_ajax') }}",
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'order': [[1, 'asc']],
            "aoColumnDefs": [
                { "sClass": "text-center", "aTargets": [ 2 ] },
            ],
            columns: [
                {   data: 'id',
                    searchable: false,
                    orderable: false,
                    className: 'text-center',
                    render: function (data, type, full, meta){
                        return '<input type="checkbox" name="id[]" class="jq_dt-checkbox" value="' + $('<div/>').text(data).html() + '">';
                    }
                },
                {   data: 'name'},
                {   data: 'logo',
                    render: function ( data, type, row ) {                        
                        return data ? '<img class="dt-logo" src="'+data+'">' : '';
                    }
                },
                {   data: 'content'},
                {   data: 'order'},                
                {   data: 'status',
                    render: function ( data, type, row ) {                        
                        return data ? '<span class="text-green">{{__('Active')}}</span>' : '<span class="text-red">{{__('Disabled')}}</span>' ;
                    }
                },                
                {   data: 'actions',
                    orderable: false,
                    render: function ( data, type, row ) {
                        var actions = '';
                                                
                        actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                        actions += '<a href="{{ url('admin/services') }}/'+row.id+'" class="btn btn-success"><i class="fa fa-eye"></i></a>';
                        actions += '</div>';
                        
                        @if(Auth::user()->hasPermissionTo('edit services'))
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                            actions += '<a href="{{ url('admin/services') }}/'+row.id+'/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
                            actions += '</div>';
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('delete services'))
                        
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-'+(row.status?"warning":"info")+' jq_service-ban" data-jq_service="'+row.id+'"><i class="fa '+(row.status?"fa-ban":"fa-asterisk")+'"></i></a>';
                            actions += '</div>';

                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-danger jq_service-delete" data-jq_service="'+row.id+'"><i class="fa fa-trash"></i></a>';
                            actions += '</div>';
                        
                        @endif
                        
                        return actions;
                    }
                }
            ]
        });    
        
        servicesTable.on( 'page.dt', function () {
            unselectAllCheckbox();
        } );
        
        servicesTable.on( 'draw.dt', function () {
            unselectAllCheckbox();
        } );
        
        $('#services-select-all').on('click', function(){           
           var rows = servicesTable.rows({ 'page': 'current','search': 'applied' }).nodes();           
           $('input[type="checkbox"]', rows).prop('checked', this.checked);   
           isCheckboxChecked();
        });
        $('#services-table tbody').on('change', 'input[type="checkbox"]', function(){           
           if(!this.checked){
              var el = $('#services-select-all').get(0);              
              if(el && el.checked && ('indeterminate' in el)){
                 el.indeterminate = true;
              }
           }
           isCheckboxChecked();
        });
        
        $(document).on('click','.jq_mass',function (e){
            e.preventDefault(); 
            var action = $(this).attr('data-action');
            var ids = isCheckboxChecked(ids);                        
            if(action == 'delete'){
                $('#jq_service-delete-form').attr('action',$('#jq_service-delete-form').attr('data-url')+ '/' + ids);
                confirmDelete();
            }else{                
                servicesBan(ids, action);
            }
        });
        
        function unselectAllCheckbox(){
            var rows = servicesTable.rows({ 'page': 'all','search': 'applied' }).nodes();           
            $('input[type="checkbox"]', rows).prop('checked', false);
            $('#services-select-all').prop('checked', false);
            $('.jq_mass').hide();
        }
        
        function isCheckboxChecked(){
            
            var ids = [];
            
            $('.jq_dt-checkbox').each(function(){                
                if($(this)[0].checked == true){
                    ids.push($(this).val());
                }                
            });
                        
            ids = ids.join(',');
            if(ids){
                $('.jq_mass').show();
            }else{
                $('.jq_mass').hide();
            }
                
            return ids;
        }
        
        $(document).on('click','.jq_service-delete',function (e){
            e.preventDefault(); 
            $('#jq_service-delete-form').attr('action',$('#jq_service-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_service'));
            confirmDelete();            
        }); 
        
        function confirmDelete(){
            var dialog = bootbox.dialog({
                title: "{{__('Are you sure you want to delete a service?')}}",
                message: "<p>{{__('All supported service info will be deleted!')}}</p>",
                buttons: {
                    cancel: {
                        label: "{{__('Cancel')}}",
                        className: 'btn-default pull-left',
                        callback: function(){
                        }
                    },                    
                    delere: {
                        label: "{{__('Delete All')}}",
                        className: 'btn-danger pull-right',
                        callback: function(){                            
                            $('#jq_service-delete-form').submit();
                        }
                    }
                }
            });
        }
        
        $(document).on('click','.jq_service-ban',function (e){
            e.preventDefault(); 
            servicesBan($(this).attr('data-jq_service'));      
        }); 
                        
        function servicesBan(ids, action=''){
            
            var href = '{{ url('admin/services') }}/'+ids+'/ban';
            
            $.get(href, {action: action, _token: $("input[name=_token]").val()})
		.done(function (data) {
                    if(data.success == 'ok'){
                        $.each( data.statuses, function( key, value ) {
                            servicesTable.rows().every( function () {
                                var d = this.data();
                                if(d.id == key){
                                    d.status = value;
                                    this.invalidate();
                                }                                
                            } );
                        });  
                        
                        servicesTable.draw();                                                
                    }                    
            });
        }
                  
      });
    </script>
@endpush