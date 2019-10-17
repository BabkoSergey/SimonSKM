<div class="modal fade" id="modal-img-set" data-related_target="">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{__('Select or Upload Image File')}}</h4>
            </div>
            <div class="modal-body">

                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title text-capitalize">{{__('Upload image file')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        {!! Form::open(array('route' => 'image.upload.post', 'files'=>true, 'id' => 'img-upload-form')) !!}   

                            {!! Form::hidden('type', null, array('id' => 'form-path-type')) !!}
                            {!! Form::hidden('sub', null, array('id' => 'form-path-sub')) !!}

                            <div class="form-group">                        
                                <div class="col-sm-12">                              
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-addon btn btn-success js-img-btn">
                                            <i class="fa fa-upload"></i>
                                        </span>
                                        {!! Form::file('image', array('class' => 'form-control js-img-fileinput')) !!}
                                        <input type="text" class="form-control js-img-filename js-img-btn" disabled value="{{__('No file selected')}}">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-primary btn-flat js-img-btn-submite" disabled>{{__('Upload and Set')}}</button>
                                        </span>                            
                                    </div>                            
                                </div>                    
                            </div>                       
                        {!! Form::close() !!}                
                    </div>
                    <!-- /.box-body -->                                
                </div>

                <!-- IMAGE LIST -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title text-capitalize">{{__('Select image file')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <ul class="users-list image-list clearfix js-img-list">

                        </ul>
                        <!-- /.image-list -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer text-center">
                        <button type="button" class="btn btn-primary js-select-and-set-img" disabled value="">{{__('Select and Set')}}</button>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!--/.box -->


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('Cancel')}}</button>                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('styles')
    <style>
        .js-img-fileinput{
            display: none !important;
        }
        .js-img-btn{
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>

        $("#modal-img-set").on('shown.bs.modal', function (e) {
            
        var type = $(e.relatedTarget).attr('data-path_type');            
        var sub = $(e.relatedTarget).attr('data-path_sub');            
            $('#form-path-type').val(type);
            $('#form-path-sub').val(sub);
            
            $(this).attr('data-related_target', $(e.relatedTarget).closest('.js-related_target').attr('id'));
        
            $('.js-img-list').html('');
            $.get('{{route('image.list.get')}}', {type: type, sub: sub, _token: $("input[name=_token]").val()})
                    .done(function (data) {
                        if(data.pathes){
                            $.each(data.pathes, function (key, url) {
                                $('.js-img-list').append('<li><div class="modal-img-block"><img src="' + data.images[key] + '" data-path="' + url + '"/></div></li>');
                            });
                        }else{
                            $.each(data.images, function (key, url) {
                                $('.js-img-list').append('<li><div class="modal-img-block"><img src="' + data.url + url + '" data-path="' + url + '"/></div></li>');
                            });
                        }
                    });

        });

        $(document).on('click', '.js-img-btn', function () {
            $('.js-select-and-set-img').prop('disabled', true);
            $(".js-img-fileinput").click();
        });

        $(document).on('change', '.js-img-fileinput', function () {
            $('.js-select-and-set-img').prop('disabled', true);
            var file = this.files[0];
            var imagefile = file.type;
            var match = ["image/jpeg", "image/png", "image/jpg"];
            if (!((imagefile == match[0]) || (imagefile == match[1]) || (imagefile == match[2]))) {
                alert("{{__('Please select a valid image file')}} JPEG/JPG/PNG !");
                $(".js-img-fileinput").val('');
                $('.js-img-filename').val("{{__('No file selected')}}");
                $('.js-img-btn-submite').prop('disabled', true);

                return false;
            }
            $('.js-img-btn-submite').prop('disabled', false);
            $('.js-img-filename').val($('.js-img-fileinput')[0].files[0].name);
        });


        $("#img-upload-form").submit(function (e) {
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
                    $('.js-img-btn-submite').prop('disabled', true);
                },
                success: function (data) {                    
                    setImg(data.url, (data.pathes ? data.pathes : data.url ), true) ;
                },
                error: function (data) {
                    alert(data.responseJSON.errors.image[0]);
                    $('button[type=submit]').prop("disabled", false);
                    if ($(".js-img-fileinput").val())
                        $('.js-img-btn-submite').prop('disabled', false);
                }
            });
        });

        $(document).on('click', '.modal-img-block', function () {
            $('.js-select-and-set-img').prop('disabled', false);
            $('.js-img-btn-submite').prop('disabled', true);
            
            $('.modal-img-block img').removeClass('active');
            $(this).find('img').addClass('active');
            $('.js-select-and-set-img').val($(this).find('img').attr('data-path'));
            $('.js-select-and-set-img').attr('src', $(this).find('img').attr('src'));
        });

        $(document).on('click', '.js-select-and-set-img', function () {
            if (!$(this).val()){
                $('.js-select-and-set-img').prop('disabled', true);
                return;
            }

            setImg($(this).attr('src'), $(this).val());
        });

        function setImg(url, src, add=false) {            
            var prefix = $("#modal-img-set").attr('data-related_target');
            prefix = prefix ? '#'+prefix+' ' : '';
            
            $(prefix+'.js-img-logo img').attr('src', url);
            $(prefix+'.js-img-set-val').val(src);
            
            if(add && $('#jq-img-galery-block-tpl').html()){
                var newElement = $('#jq-img-galery-block-tpl .jq-img-galery-element').first().clone();
                newElement.find('img').attr('src',url);
                newElement.find('.jq-img-galery-remove').attr('data-img_remove',src);
                $('#jq-img-galery-block').append(newElement);
            }
            
            $('button[type=submit]').prop("disabled", false);
            $('.js-img-btn-submite').prop('disabled', true);
            $(".js-img-fileinput").val('');
            $('.js-img-filename').val("{{__('No file selected')}}");
            $('.modal-img-block').html('');
            $('.js-select-and-set-img').val('');

            $('#modal-img-set').modal('hide');
        }
    </script>
@endpush
