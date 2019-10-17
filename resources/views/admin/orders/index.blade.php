@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Orders') }} @endsection

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
                <table id="orders-table" class="table table-bordered table-striped tabel-mass-actions">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="orders-select-all"></th>
                            <th>{{__('Order')}}</th>                            
                            <th>{{__('Customer')}}</th>                                                         
                            <th>{{__('From')}}</th>
                            <th>{{__('Total')}}</th>
                            <!--<th>{{__('Payment')}}</th>-->       
                            <th>{{__('Type')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Created')}}</th>                             
                            <th>{{__('Updated')}}</th>
                            <th style="width: 175px;">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>{{__('Order')}}</th>                            
                            <th>{{__('Customer')}}</th>                                                         
                            <th>{{__('Date')}}</th>
                            <th>{{__('Total')}}</th>
                            <!--<th>{{__('Payment')}}</th>-->       
                            <th>{{__('Type')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Created')}}</th>                             
                            <th>{{__('Updated')}}</th>
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

@if(Auth::user()->hasPermissionTo('delete orders'))
<div style="display: none">
    <form id="jq_order-delete-form" method="POST" action="" data-url="{{ url('/admin/orders/') }}" accept-charset="UTF-8">
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
          
        @if(Auth::user()->hasPermissionTo('add orders'))
            $("#orders-table").one("preInit.dt", function () {

                $button = $('<a class="btn btn-success margin-l-10" role="button" href="{{ route('orders.create') }}"> {{ __('Add New Order') }}</a>');
                $("#orders-table_filter label").append($button);
                $button.button();

            });                                    
        @endif
        
        @if(Auth::user()->hasAnyPermission(['delete orders', 'edit orders']))
            $("#orders-table").one("preInit.dt", function () {
                var buttonDelete = buttonHold = buttonActive = '';
                
                @if(Auth::user()->hasPermissionTo('delete orders'))
                    buttonDelete = '<a class="btn btn-danger margin-r-10 jq_mass" data-action="delete" role="button" href=""><i class="fa fa-trash"></i></a>';
                @endif
                                               
                $("#orders-table_filter label").prepend('<div class="dt-mass-block">'+buttonDelete+'</div>');
                $button.button();
            }); 
        @endif
        
        var ordersTable = $('#orders-table').DataTable({
            ajax: "{{ url('admin/orders_dt_ajax') }}",
            'paging'      : true,
            'lengthChange': true,
            'searching'   : true,
            'ordering'    : true,
            'info'        : true,
            'autoWidth'   : false,
            'order': [[1, 'desc']],
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
                {   data: 'id'},
                {   data: 'user',
                    render: function ( data, type, row ) {                        
                        return data ? (data.first_name || data.last_name ? data.first_name +' '+ data.last_name : data.name) : '';
                    }
                },
                {   data: 'from',
                    render: function ( data, type, row ) {     
                        var formatData = '';
                        $.each( data , function( key, value ) {     
                            formatData += '<p">'+value+'</p>';
                        });
                        return formatData;                     
                    }
                },
                {   data: 'price',
                    render: function ( data, type, row ) {                        
                        return row.price ? row.price - row.discounts : 0;
                    }
                },    
                {   data: 'payment_status'},
//                {   data: 'order_type'},
                {   data: 'order_status'},
                {   data: 'created_at'},
                {   data: 'updated_at'},                
                {   data: 'actions',
                    orderable: false,
                    render: function ( data, type, row ) {
                        var actions = '';
                                                
                        actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                        actions += '<a href="{{ url('admin/orders') }}/'+row.id+'" class="btn btn-success"><i class="fa fa-eye"></i></a>';
                        actions += '</div>';
                        
                        @if(Auth::user()->hasPermissionTo('edit orders'))
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                            actions += '<a href="{{ url('admin/orders') }}/'+row.id+'/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
                            actions += '</div>';
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('delete orders'))                   
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-danger jq_order-delete" data-jq_order="'+row.id+'"><i class="fa fa-trash"></i></a>';
                            actions += '</div>';                        
                        @endif
                        
                        return actions;
                    }
                }
            ]
        });    
        
        ordersTable.on( 'page.dt', function () {
            unselectAllCheckbox();
        } );
        
        ordersTable.on( 'draw.dt', function () {
            unselectAllCheckbox();
        } );
        
        $('#orders-select-all').on('click', function(){           
           var rows = ordersTable.rows({ 'page': 'current','search': 'applied' }).nodes();           
           $('input[type="checkbox"]', rows).prop('checked', this.checked);   
           isCheckboxChecked();
        });
        $('#orders-table tbody').on('change', 'input[type="checkbox"]', function(){           
           if(!this.checked){
              var el = $('#orders-select-all').get(0);              
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
                $('#jq_order-delete-form').attr('action',$('#jq_order-delete-form').attr('data-url')+ '/' + ids);
                confirmDelete();
            }
            
        });
        
        function unselectAllCheckbox(){
            var rows = ordersTable.rows({ 'page': 'all','search': 'applied' }).nodes();           
            $('input[type="checkbox"]', rows).prop('checked', false);
            $('#orders-select-all').prop('checked', false);
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
        
        $(document).on('click','.jq_order-delete',function (e){
            e.preventDefault(); 
            $('#jq_order-delete-form').attr('action',$('#jq_order-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_order'));
            confirmDelete();            
        }); 
        
        function confirmDelete(){
            var dialog = bootbox.dialog({
                title: "{{__('Are you sure you want to delete order?')}}",
                message: "<p>{{__('All supported order info will be deleted!')}}</p>",
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
                            $('#jq_order-delete-form').submit();
                        }
                    }
                }
            });
        }        
       
      });
    </script>
@endpush