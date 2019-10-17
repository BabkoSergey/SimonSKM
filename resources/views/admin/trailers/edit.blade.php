@extends('admin.layouts.app')

@section('htmlheader_title') {{ $trailer->name }} @endsection

@section('sub_title') {{ __('Edit') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @include('admin.templates.action_notifi')
        
        <!-- form start -->            
                        
        {!! Form::model($trailer, ['method' => 'PATCH','route' => ['trailers.update', $trailer->id], 'class' => 'form-horizontal'] ) !!}        
            <!-- Horizontal Form -->
            <div class="box box-info">            
                <div class="box-header with-border">
                    
                </div><!-- /.box-header -->
            
                <div class="box-body">
                                        
                    <div class="form-group">
                        <label for="parent" class="col-sm-2 control-label">{{__('Name')}}*</label>
                        
                        <div class="col-sm-10">                            
                            {!! Form::text('name', $trailer->name, array('placeholder' => __('Name'),'class' => 'form-control', 'required' )) !!}                            
                        </div>                        
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">{{__('Price')}}</label>

                        <div class="col-sm-10">
                            {!! Form::number('price', $trailer->price, array('placeholder' => __('Price'),'class' => 'form-control', 'min'=>0, 'id'=>'price', 'step'=>'0.01')) !!}                                      
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="gallery" class="col-sm-2 control-label">
                            {{__('Gallery')}}
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-imgs" data-path_type="trailer" data-path_sub="{{$trailer->id}}"><i class="fa fa-upload"></i></button>
                            </span>
                        </label>

                        <div class="col-sm-10" style="display: flex;flex-wrap: wrap;" id="jq-img-galery-block">
                            @foreach($images['images'] as $key=>$image)
                                <div class="col-sm-3 jq-img-galery-element" >
                                    <img src="{{$image}}" style="width: 90%;"/>
                                    <button type="button" class="close btn-danger jq-img-galery-remove" data-img_remove="{{$images['pathes'][$key]}}">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endforeach
                        </div>                        
                        <div style="display: none;" id="jq-img-galery-block-tpl">                            
                            <div class="col-sm-3 jq-img-galery-element" >
                                <img src="" style="width: 90%;"/>
                                <button type="button" class="close btn-danger jq-img-galery-remove" data-img_remove="">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Custom Tabs (Pulled to the right) -->    
                    <div class="box-header with-border margin-b-10">
                        <h3 class="box-title">{{__('Car specification')}}</h3>
                    </div><!-- /.box-header -->
                
                    <div class="nav-tabs-custom inside-box">
                        <ul class="nav nav-tabs">
                            @foreach($locales['avalible'] as $locale)
                                <li class="{{ $locale == $locales['def'] ? 'active' : '' }}">
                                    <a href="#tab_{{$locale}}" data-toggle="tab">
                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$locale}}" alt="{{$locale}}" />
                                        <span class="text-capitalize padding-l-10">{{__($locale.'_'.$locale)}}</span>
                                        {{ $locale == $locales['def'] ? '*' : '' }}
                                    </a>
                                </li>                                               
                            @endforeach
                        </ul>
                        
                        <div class="tab-content">
                            @foreach($locales['avalible'] as $locale)
                            
                                @php $spec = $trailer->contents()->where('locale', $locale)->first() ? $trailer->contents()->where('locale', $locale)->first()->spec ? unserialize($trailer->contents()->where('locale', $locale)->first()->spec) : [] : []; @endphp                                                        
                                
                                <div class="tab-pane {{ $locale == $locales['def'] ? 'active' : '' }}" id="tab_{{$locale}}">
                                    <div class="row jq-spec" data-count="{{count($spec)}}" data-locale="{{$locale}}">      
                                        <div class="col-sm-12 margin-b-10">
                                            <a class="btn btn-success pull-right row-add" role="button" href="">{{ __('Add New') }} </i></a>
                                        </div>
                                        
                                        @foreach($spec as $codeKey=>$code)
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="code_{{$codeKey}}_{{$locale}}_code" class="col-sm-2 control-label">{{ __('Name')}}</label>

                                                    <div class="col-sm-3">                                                        
                                                        {!! Form::text($locale.'[spec]['.$codeKey.'][code]', $code['code'] ?? null, array('class' => 'form-control', 'required' )) !!}
                                                    </div>
                                                    
                                                    <label for="code_{{$codeKey}}_{{$locale}}_val" class="col-sm-2 control-label">{{ __('Value')}}</label>

                                                    <div class="col-sm-4">                                                        
                                                        {!! Form::text($locale.'[spec]['.$codeKey.'][val]', $code['val'] ?? null, array('class' => 'form-control' )) !!}
                                                    </div>
                                                    
                                                    <div class="col-sm-1">
                                                        <a class="btn btn-danger pull-right row-delete" role="button" href=""><i class="fa fa-trash"></i></a>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        @endforeach                                                                                
                                    </div>         
                                    
                                </div>
                            <!-- /.tab-pane -->                            
                            @endforeach
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->

                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">{{__('Description')}}</label>

                        <div class="col-sm-10">
                            {!! Form::textarea('description', $trailer->description, array('placeholder' => __('Description'),'class' => 'form-control', 'rows'=> '3', 'id' => 'cke-description' )) !!}                            
                        </div>
                    </div>
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('trailers.index') }}">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                </div><!-- /.box-footer -->
            
            </div><!-- /.box -->
        {!! Form::close() !!}
       
    </div>
</div>

<div id="jq-spec-template" style="display: none">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="code_0_de_code" class="col-sm-2 control-label jq-row-code">{{ __('Name')}}</label>

            <div class="col-sm-3">                                                        
                <input class="form-control jq-row-code" name="de[spec][0][code]" type="text" value="" required>
            </div>

            <label for="code_0_de_val" class="col-sm-2 control-label jq-row-val">{{ __('Value')}}</label>

            <div class="col-sm-4">                                                        
                <input class="form-control jq-row-val" name="de[spec][0][val]" type="text" value="">
            </div>

            <div class="col-sm-1">
                <a class="btn btn-danger pull-right row-delete" role="button" href=""><i class="fa fa-trash"></i></a>
            </div>

        </div>
    </div>
</div>

@include('admin.modals.img_galery_upload')

@endsection

@push('styles')
    
@endpush

@push('scripts')   

    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace( 'cke-description' );
    </script>
    
    <script>
        $(function () {  
            $( document ).ready(function() {
                $(document).on('change','#price',function (){                
                    $(this).val(checkDec($(this).val()));      
                });

                $(document).on('keyup','#price',function (){                
                    $(this).val(checkDec($(this).val()));      
                });
            });
                        
            $(document).on('click','.row-add', function (e) {
                e.preventDefault();
                
                var specCount = $(this).closest('.jq-spec').attr('data-count');
                var specLocale = $(this).closest('.jq-spec').attr('data-locale');                                
                var clone = $('#jq-spec-template');       
                
                specCount++;
                $(this).closest('.jq-spec').attr('data-count',specCount);
                
                clone.find('label.jq-row-code').attr('for', 'code_'+specCount+'_'+specLocale+'_code');
                clone.find('input.jq-row-code').attr('name', specLocale+'[spec]['+specCount+'][code]');
                clone.find('label.jq-row-val').attr('for', 'code_'+specCount+'_'+specLocale+'_val');
                clone.find('input.jq-row-val').attr('name', specLocale+'[spec]['+specCount+'][val]');
                
                $(this).closest('.jq-spec').append(clone.html());
            });
            
            $(document).on('click','.row-delete', function (e) {
                e.preventDefault();
                $(this).closest('.col-sm-12').remove();
            });
            
            $(document).on('click','.jq-img-galery-remove', function (e) {
                e.preventDefault();
                var el = $(this);
                var path = el.attr('data-img_remove')
                
                $.post('{{route('image.remove.post')}}', {path: path, type: 'trailer', sub: {{$trailer->id}}, _token: $("input[name=_token]").val() })
                    .done(function (data) {
                        el.closest('.jq-img-galery-element').remove();                                                
                    });
            });
                        
        });
    </script>
@endpush