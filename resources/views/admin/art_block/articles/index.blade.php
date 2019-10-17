@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Articles') }} @endsection

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
                <table id="articles-table" class="table table-bordered table-striped tabel-mass-actions">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="articles-select-all"></th>
                            <th>{{__('Article')}}</th>
                            <th>{{__('Logo')}}</th>
                            @if(Auth::user()->hasPermissionTo('show art_categorys'))
                                <!--<th>{{__('Category')}}</th>-->                             
                            @endif
                            <th>{{__('Content')}}</th>                             
                            <th>{{__('Created')}}</th>                             
                            <th>{{__('Updated')}}</th>
                            <th>{{__('Status')}}</th>
                            <th style="width: 175px;">{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>{{__('Article name')}}</th>
                            <th>{{__('Logo')}}</th>
                            @if(Auth::user()->hasPermissionTo('show art_categorys'))
                                <!--<th>{{__('Category')}}</th>-->                             
                            @endif
                            <th>{{__('Content')}}</th>                                                       
                            <th>{{__('Created')}}</th>                             
                            <th>{{__('Updated')}}</th>
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

@if(Auth::user()->hasPermissionTo('delete articles'))
<div style="display: none">
    <form id="jq_article-delete-form" method="POST" action="" data-url="{{ url('/admin/articles/') }}" accept-charset="UTF-8">
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
          
        @if(Auth::user()->hasPermissionTo('add articles'))
            $("#articles-table").one("preInit.dt", function () {

                $button = $('<a class="btn btn-success margin-l-10" role="button" href="{{ route('articles.create') }}"> {{ __('Add New Article') }}</a>');
                $("#articles-table_filter label").append($button);
                $button.button();

            });                                    
        @endif
        
        @if(Auth::user()->hasAnyPermission(['delete articles', 'edit articles']))
            $("#articles-table").one("preInit.dt", function () {
                var buttonDelete = buttonHold = buttonActive = '';
                
                @if(Auth::user()->hasPermissionTo('delete articles'))
                    buttonDelete = '<a class="btn btn-danger margin-r-10 jq_mass" data-action="delete" role="button" href=""><i class="fa fa-trash"></i></a>';
                @endif
                
                @if(Auth::user()->hasPermissionTo('edit articles'))
                    buttonHold = '<a class="btn btn-warning margin-r-10 jq_mass" data-action="hold" role="button" href=""><i class="fa fa-ban"></i></a>';
                    buttonActive = '<a class="btn btn-info margin-r-10 jq_mass" data-action="activate" role="button" href=""><i class="fa fa-asterisk"></i></a>';
                @endif
                
                $("#articles-table_filter label").prepend('<div class="dt-mass-block">'+buttonActive+buttonHold+buttonDelete+'</div>');
                $button.button();
            }); 
        @endif
        
        var articlesTable = $('#articles-table').DataTable({
            ajax: "{{ url('admin/articles_dt_ajax') }}",
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
                @if(Auth::user()->hasPermissionTo('show art_categorys'))
//                    {   data: 'category'},              
                @endif
                {   data: 'content'},     
                {   data: 'created_at'},              
                {   data: 'updated_at'},     
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
                        actions += '<a href="{{ url('admin/articles') }}/'+row.id+'" class="btn btn-success"><i class="fa fa-eye"></i></a>';
                        actions += '</div>';
                        
                        @if(Auth::user()->hasPermissionTo('edit articles'))
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';
                            actions += '<a href="{{ url('admin/articles') }}/'+row.id+'/edit" class="btn btn-primary"><i class="fa fa-pencil"></i></a>';
                            actions += '</div>';
                        @endif
                        
                        @if(Auth::user()->hasPermissionTo('delete articles'))
                        
                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-'+(row.status?"warning":"info")+' jq_article-ban" data-jq_article="'+row.id+'"><i class="fa '+(row.status?"fa-ban":"fa-asterisk")+'"></i></a>';
                            actions += '</div>';

                            actions += '<div class="btn-group btn-group-sm pull-right" style="margin:0 5px;" role="group" aria-label="Basic example">';                        
                            actions += '<a href="" class="btn btn-danger jq_article-delete" data-jq_article="'+row.id+'"><i class="fa fa-trash"></i></a>';
                            actions += '</div>';
                        
                        @endif
                        
                        return actions;
                    }
                }
            ]
        });    
        
        articlesTable.on( 'page.dt', function () {
            unselectAllCheckbox();
        } );
        
        articlesTable.on( 'draw.dt', function () {
            unselectAllCheckbox();
        } );
        
        $('#articles-select-all').on('click', function(){           
           var rows = articlesTable.rows({ 'page': 'current','search': 'applied' }).nodes();           
           $('input[type="checkbox"]', rows).prop('checked', this.checked);   
           isCheckboxChecked();
        });
        $('#articles-table tbody').on('change', 'input[type="checkbox"]', function(){           
           if(!this.checked){
              var el = $('#articles-select-all').get(0);              
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
                $('#jq_article-delete-form').attr('action',$('#jq_article-delete-form').attr('data-url')+ '/' + ids);
                confirmDelete();
            }else{                
                articlesBan(ids, action);
            }
        });
        
        function unselectAllCheckbox(){
            var rows = articlesTable.rows({ 'page': 'all','search': 'applied' }).nodes();           
            $('input[type="checkbox"]', rows).prop('checked', false);
            $('#articles-select-all').prop('checked', false);
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
        
        $(document).on('click','.jq_article-delete',function (e){
            e.preventDefault(); 
            $('#jq_article-delete-form').attr('action',$('#jq_article-delete-form').attr('data-url')+ '/' + $(this).attr('data-jq_article'));
            confirmDelete();            
        }); 
        
        function confirmDelete(){
            var dialog = bootbox.dialog({
                title: "{{__('Are you sure you want to delete a article?')}}",
                message: "<p>{{__('All supported article info will be deleted!')}}</p>",
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
                            $('#jq_article-delete-form').submit();
                        }
                    }
                }
            });
        }
        
        $(document).on('click','.jq_article-ban',function (e){
            e.preventDefault(); 
            articlesBan($(this).attr('data-jq_article'));      
        }); 
                        
        function articlesBan(ids, action=''){
            
            var href = '{{ url('admin/articles') }}/'+ids+'/ban';
            
            $.get(href, {action: action, _token: $("input[name=_token]").val()})
		.done(function (data) {
                    if(data.success == 'ok'){
                        $.each( data.statuses, function( key, value ) {
                            articlesTable.rows().every( function () {
                                var d = this.data();
                                if(d.id == key){
                                    d.status = value;
                                    this.invalidate();
                                }                                
                            } );
                        });  
                        
                        articlesTable.draw();                                                
                    }                    
            });
        }
                  
      });
    </script>
@endpush