<div class="modal fade" id="modal-auto-key">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{__('Add New')}}</h4>
            </div>
            <div class="modal-body">

                <form class="form-horizontal">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title text-capitalize">{{__('Car parameters')}} <b>code</b></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding form-horizontal"> 
                            <div class="box-body">                                                                
                                <div class="form-group">                                    
                                    <div class="col-sm-12">
                                        <div class="input-group">
                                            <input type="text" value="" id="js-new-code" maxlength="8" class="form-control">
                                        </div>

                                    </div>

                                </div>
                            </div><!-- /.box-body -->            
                        </div>
                        <!-- /.box-body -->                                
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('Cancel')}}</button>                
                <button type="button" class="btn btn-info pull-right" id="js-new-code-save">{{ __('Save') }}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('styles')
    <style>
        
    </style>
@endpush

@push('scripts')
    <script>
        var type;

        $("#modal-auto-key").on('shown.bs.modal', function (e) {
            $('#js-new-code').val('');
            
            type = [];
            
            $('.jq-auto-code').each(function(){
                type.push($(this).attr('data-code'));
            });        
        });
        
        $(document).on('change','#js-new-code',function (){                
            $(this).val(codefy($(this).val()));      
        });
            
        $(document).on('keyup','#js-new-code',function (){                
            $(this).val(codefy($(this).val()));      
        });
        
        $(document).on('click','#js-new-code-save',function (){                
            var code = $('#js-new-code').val();
            if(!code){
                alert('Code is empty!');
                return false;
            }
            
            if($.inArray( code, type ) >= 0){
                alert('Code already exist!');
                return false;
            }
            
            var clone = $('#jq-auto-code-block-template');            
            
            clone.find('.jq-auto-code').attr('data-code',code).text(code+':');
            clone.find('.input-group').each(function(){
                var leng = $(this).find('img').attr('alt');
                $(this).find('input').attr('name','autos-'+code+'-'+leng);
            });
            
            $('.jq-auto-code-block').append(clone.html());
            
            $("#modal-auto-key").modal('hide');
        
        });
        
    </script>
@endpush
