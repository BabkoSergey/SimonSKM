<div class="modal fade" id="modal-imgs" data-related_target="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{__('Upload Image File')}}</h4>
            </div>
            {!! Form::open(array('route' => 'image.upload.multi.post', 'files'=>true, 'id' => 'galery-upload-form')) !!}   
            <div class="modal-body">
                <div class="box box-success">
                    <!-- /.box-header -->
                    <div class="box-body">
                            {!! Form::hidden('type', null, array('id' => 'form-g-path-type')) !!}
                            {!! Form::hidden('sub', null, array('id' => 'form-g-path-sub')) !!}
                            <div class="form-group">                        
                                <div class="col-sm-12">                              
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon btn btn-success js-galery-btn">
                                            <i class="fa fa-upload"></i>
                                        </span>                                        
                                        {!! Form::file('gallery[]', array('multiple'=>true,'accept'=>'image/*', 'class' => 'form-control js-galery-fileinput'));  !!}                                        
                                        <span class="form-control js-galery-filename js-galery-btn"style="display:inline-table">{{__('No file selected')}}</span>
                                    </div>                            
                                </div>                    
                            </div>                                               
                    </div>
                    <!-- /.box-body -->                                
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">{{__('Cancel')}}</button>                
                <button type="submit" class="btn btn-primary btn-flat js-galery-btn-submite" disabled>{{__('Upload')}}</button>
            </div>
            {!! Form::close() !!}                
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('styles')
    <style>
        .js-galery-fileinput{
            display: none !important;
        }
        .js-galery-btn{
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        var filesAr = [];

        $("#modal-imgs").on('shown.bs.modal', function (e) {
        
        var type = $(e.relatedTarget).attr('data-path_type');            
        var sub = $(e.relatedTarget).attr('data-path_sub');            
            $('#form-g-path-type').val(type);
            $('#form-g-path-sub').val(sub);
            $('.js-galery-filename').text("{{__('No file selected')}}");
            
            $(this).attr('data-related_target', $(e.relatedTarget).closest('.js-related_target').attr('id'));        
        });

        $(document).on('click', '.js-galery-btn', function () {            
            $(".js-galery-fileinput").click();
        });

        $(document).on('change', '.js-galery-fileinput', function () {            
            var filesInput = this.files;                        
            var error = [];
            var match = ["image/jpeg", "image/png", "image/jpg"];
            $('.js-galery-filename').text('');

            $.each(filesInput,function(key, file){                                
                var imagefile = file.type;                
                if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {                    
                    error.push(file.name + ' ');                    
                }else{
                    $('.js-galery-filename').text($('.js-galery-filename').text() + ' ' + file.name);
                }
            });
            
            if(error.length > 0){
                var list = '';
                $.each(error,function(key, err){
                    list += err + ' ';
                });
                $('.js-galery-filename').text('{{__('Please select a valid image file')}} JPEG/JPG/PNG: '+ list);
                $(".js-galery-fileinput").val('');
                $('.js-galery-btn-submite').prop('disabled', true);                
            }else{
                $('.js-galery-btn-submite').prop('disabled', false);
            }
        });


        $("#galery-upload-form").submit(function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: $(this).prop("action"),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $('button[type=submit]').prop("disabled", true);
                    $('.js-galery-btn-submite').prop('disabled', true);
                },
                success: function (data) {     
                    if(data.imgs){
                        $.each(data.imgs,function(key, img){
                            setImgs(img.url, (img.pathes ? img.pathes : img.url ), true) ;
                        });
                    }
                },
                error: function (data) {
                    var er = '';
                    $.each(data.responseJSON.errors,function(key, val){
                        er += val + '; ';
                    });
                    alert(er);
                    $('button[type=submit]').prop("disabled", false);
                    if ($(".js-galery-fileinput").text())
                        $('.js-galery-btn-submite').prop('disabled', false);
                }
            });
        });

        function setImgs(url, src) {            
            var prefix = $("#modal-imgs").attr('data-related_target');
            prefix = prefix ? '#'+prefix+' ' : '';
            
            if($('#jq-img-galery-block-tpl').html()){
                var newElement = $('#jq-img-galery-block-tpl .jq-img-galery-element').first().clone();
                newElement.find('img').attr('src',url);
                newElement.find('.jq-img-galery-remove').attr('data-img_remove',src);
                $('#jq-img-galery-block').append(newElement);
            }
            
            $('button[type=submit]').prop("disabled", false);
            $('.js-galery-btn-submite').prop('disabled', true);
            $(".js-galery-fileinput").val('');
            $('.js-galery-filename').text("{{__('No file selected')}}");            

            $('#modal-imgs').modal('hide');
        }
    </script>
@endpush
