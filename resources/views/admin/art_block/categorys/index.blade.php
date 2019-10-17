@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Categorys') }} @endsection

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
                <table id="art_categorys-table" class="table table-bordered table-striped tabel-mass-actions">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="art_categorys-select-all"></th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Logo')}}</th>
                            <th>{{__('Articles')}}</th>
                            <th>{{__('Parent Category')}}</th>                             
                            <th>{{__('Status')}}</th>
                            <th style="width: 175px;">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Logo')}}</th> 
                            <th>{{__('Articles')}}</th>
                            <th>{{__('Parent Category')}}</th>                             
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

@if(Auth::user()->hasPermissionTo('delete art_categorys'))
<div style="display: none">
    <form id="jq_art_categorys-delete-form" method="POST" action="" data-url="{{ url('/admin/art_categorys/') }}" accept-charset="UTF-8">
        @csrf
        <input name="_method" type="hidden" value="DELETE">   
        <input name="is_soft" type="hidden" value="">
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
          
        @if(Auth::user()->hasPermissionTo('add art_categorys'))
            $("#art_categorys-table").one("preInit.dt", function () {

                $button = $('<a class="btn btn-success margin-l-10" role="button" href="{{ route('art_categorys.create') }}"> {{ __('Add New Category') }}</a>');
                $("#art_categorys-table_filter label").append($button);
                $button.button();

            });                                    
        @endif
        
        @if(Auth::user()->hasAnyPermission(['delete art_categorys', 'edit art_categorys']))
            $("#art_categorys-table").one("preInit.dt", function () {
                var buttonDelete = buttonHold = buttonActive = '';
                
                @if(Auth::user()->hasPermissionTo('delete art_categorys'))
                    buttonDelete = '<a class="btn btn-danger margin-r-10 jq_mass" data-action="delete" role="button" href=""><i class="fa fa-trash"></i></a>';
                @endif
                
                @if(Auth::user()->hasPermissionTo('edit art_categorys'))
                    buttonHold = '<a class="btn btn-warning margin-r-10 jq_mass" data-action="hold" role="button" href=""><i class="fa fa-ban"></i></a>';
                    buttonActive = '<a class="btn btn-info margin-r-10 jq_mass" data-action="activate" role="button" href=""><i class="fa fa-asterisk"></i></a>';
                @endif
                
                $("#art_categorys-table_filter label").prepend('<div class="dt-mass-block">'+buttonActive+buttonHold+buttonDelete+'</div>');
                $button.button();
            }); 
        @endif
        
        var art_categorysTable = $('#art_categorys-table').DataTable({
            ajax: "{{ url('admin/art_categorys_dt_ajax') }}",
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
                {   data: 'articles'},                
                {   data: 'parent'},                
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
                        actions += '<a href="{{ url('admin/art_categorys') }}/'+row.id+'" class="btn btn-success"><i class="fa fa-eye"></i></a>';
                        actions += '</div>';
                        
                        @if(Auth::user()->hasPermissionTo('edit art_categorys'))
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                            actions += '<a href="{{ url('admin/art_categorys') }}/'+row.id+'/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
                            actions += '</div>';
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('delete art_categorys'))
                        
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-'+(row.status?"warning":"info")+' jq_art_categorys-ban" data-jq_art_categorys="'+row.id+'"><i class="fa '+(row.status?"fa-ban":"fa-asterisk")+'"></i></a>';
                            actions += '</div>';

                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-danger jq_art_categorys-delete" data-jq_art_categorys="'+row.id+'"><i class="fa fa-trash"></i></a>';
                            actions += '</div>';
                        
                        @endif
                        
                        return actions;
                    }
                }
            ]
        });    
        
        art_categorysTable.on( 'page.dt', function () {
            unselectAllCheckbox();
        } );
        
        art_categorysTable.on( 'draw.dt', function () {
            unselectAllCheckbox();
        } );
        
        $('#art_categorys-select-all').on('click', function(){           
           var rows = art_categorysTable.rows({ 'page': 'current','search': 'applied' }).nodes();           
           $('input[type="checkbox"]', rows).prop('checked', this.checked);   
           isCheckboxChecked();
        });
        $('#art_categorys-table tbody').on('change', 'input[type="checkbox"]', function(){           
           if(!this.checked){
              var el = $('#art_categorys-select-all').get(0);              
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
                $('#jq_art_categorys-delete-form').attr('action',$('#jq_art_categorys-delete-form').attr('data-url')+ '/' + ids);
                confirmDelete();
            }else{                
                art_categorysBan(ids, action);
            }
        });
        
        function unselectAllCheckbox(){
            var rows = art_categorysTable.rows({ 'page': 'all','search': 'applied' }).nodes();           
            $('input[type="checkbox"]', rows).prop('checked', false);
            $('#art_categorys-select-all').prop('checked', false);
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
        
        $(document).on('click','.jq_art_categorys-delete',function (e){
            e.preventDefault(); 
            $('#jq_art_categorys-delete-form').attr('action',$('#jq_art_categorys-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_art_categorys'));
            confirmDelete();            
        }); 
        
        function confirmDelete(){
            var dialog = bootbox.dialog({
                title: "{{__('Are you sure you want to delete a category?')}}",
                message: "<p>{{__('Save subcategories and move one Level Up or delete along with subcategories? All articles from this category (subcategories with articles for the Delete All option) will be deleted!')}} <br>{{__('Choose one of the following options.')}}</p>",
                buttons: {
                    cancel: {
                        label: "{{__('Cancel')}}",
                        className: 'btn-default pull-left',
                        callback: function(){
                        }
                    },   
                     levelUp: {
                        label: "{{__('Level Up')}}",
                        className: 'btn-warning pull-right',
                        callback: function(){
                            $('#jq_art_categorys-delete-form input[name=is_soft]').val(1);
                            $('#jq_art_categorys-delete-form').submit();
                        }
                    },
                    delere: {
                        label: "{{__('Delete All')}}",
                        className: 'btn-danger pull-right',
                        callback: function(){
                            $('#jq_art_categorys-delete-form input[name=is_soft]').val(0);
                            $('#jq_art_categorys-delete-form').submit();
                        }
                    }
                }
            });
        }
        
        $(document).on('click','.jq_art_categorys-ban',function (e){
            e.preventDefault(); 
            art_categorysBan($(this).attr('data-jq_art_categorys'));      
        }); 
                        
        function art_categorysBan(ids, action=''){
            
            var href = '{{ url('admin/art_categorys') }}/'+ids+'/ban';
            
            $.get(href, {action: action, _token: $("input[name=_token]").val()})
		.done(function (data) {
                    if(data.success == 'ok'){
                        $.each( data.statuses, function( key, value ) {
                            art_categorysTable.rows().every( function () {
                                var d = this.data();
                                if(d.id == key){
                                    d.status = value;
                                    this.invalidate();
                                }                                
                            } );
                        });  
                        
                        art_categorysTable.draw();                                                
                    }                    
            });
        }
                  
      });
    </script>
@endpush