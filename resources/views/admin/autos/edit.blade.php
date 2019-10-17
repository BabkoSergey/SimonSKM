@extends('admin.layouts.app')

@section('htmlheader_title') {{ $auto->brand }} <small><b>{{ $auto->model }} ({{ $auto->release }})</b></small> @endsection

@section('sub_title') {{ __('Edit') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @include('admin.templates.action_notifi')
        
        <!-- form start -->            

        {!! Form::model($auto, ['method' => 'PATCH','route' => ['cars.update', $auto->id], 'class' => 'form-horizontal'] ) !!}        
            <!-- Horizontal Form -->
            <div class="box box-info">            
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('Common parameters')}}</h3>
                </div><!-- /.box-header -->
                
                <div class="box-body">
                    <div class="form-group">
                        <label for="brand" class="col-sm-2 control-label">{{__('Brand')}}*</label>

                        <div class="col-sm-10">                            
                            {!! Form::select('brand', $brands, $auto->brand, array('class' => 'form-control select2-brands','single', 'placeholder' => '...', 'required' )) !!}
                        </div>                        
                    </div>

                    <div class="form-group">
                        <label for="model" class="col-sm-2 control-label">{{__('Model')}}*</label>

                        <div class="col-sm-10">                            
                            {!! Form::select('model', $models, $auto->model, array('class' => 'form-control select2-model','single', 'placeholder' => '...', 'required' )) !!}                            
                        </div>                        
                    </div>

                    <div class="form-group">
                        <label for="release" class="col-sm-2 control-label">{{__('Release year')}}*</label>

                        <div class="col-sm-10">
                            {!! Form::text('release', $auto->release, array('placeholder' => __('Release year'),'class' => 'form-control', 'required' )) !!}                            
                        </div>                        
                    </div>
                                
                    <div class="form-group">
                        <label for="logo" class="col-sm-2 control-label">{{__('Car main logo')}}</label>

                        <div class="col-sm-10">
                            <div class="input-group js-related_target" id="js-related_target-logo" data-path="{{$auto->logo ?? '' }}">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default img-logo js-img-logo">                                        
                                        <img src="{{$auto->logo ? '/storage/'.$auto->logo : asset('/img/blank_flag.gif')}}">
                                    </button>
                                </div>
                                <!-- /btn-group -->
                                {!! Form::text('logo', null, array('placeholder' => __('Select/Upload file or set absolute Url path'),'class' => 'form-control js-img-set-val')) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-img-set" data-path_type="car" data-path_sub="{{$auto->id}}"><i class="fa fa-upload"></i></button>
                                </span>                                
                            </div>     
                            <span class="help-block text-center">{{__('Select/Upload file or set absolute Url path')}}</span>
                        </div>
                    </div>
                                        
                    <div class="form-group">
                        <label for="mileage" class="col-sm-2 control-label">
                            {{__('Gallery')}}
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-imgs" data-path_type="car" data-path_sub="{{$auto->id}}"><i class="fa fa-upload"></i></button>
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
                    
                    <div class="form-group">
                        <label for="mileage" class="col-sm-2 control-label">{{__('Mileage')}}</label>

                        <div class="col-sm-10">
                            {!! Form::number('mileage', $auto->mileage, array('placeholder' => __('Mileage'),'class' => 'form-control', 'min' => 0, 'required' )) !!}                            
                        </div>                        
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">{{__('Price')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::number('price', $auto->price, array('placeholder' => __('Price'),'class' => 'form-control', 'min'=>0, 'id'=>'price', 'step'=>'0.01', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="range" class="col-sm-2 control-label">{{__('Range')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::number('range', $auto->range, array('placeholder' => __('Range'),'class' => 'form-control', 'min'=> 0, 'max' => 5, 'id'=>'range', 'step'=>'0.1', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="show" class="col-sm-2 control-label">{{__('Is Show')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('show', array(0=> __('No'), 1=> __('Yes') ), $auto->show, array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="sale" class="col-sm-2 control-label">{{__('Is Sale')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('sale', array(0=> __('No'), 1=> __('Yes') ), $auto->sale, array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="ria" class="col-sm-2 control-label">{{__('Is Auto.RIA published')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('ria', array(0=> __('No'), 1=> __('Yes') ), $auto->ria, array('class' => 'form-control','single', 'required')) !!}                            
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
                            
                                <div class="tab-pane {{ $locale == $locales['def'] ? 'active' : '' }}" id="tab_{{$locale}}">
                                    <div class="row">
                                        @php $spec = unserialize($auto->contents()->where('locale', $locale)->first()->spec ?? ''); @endphp                                                        
                                        @foreach($settings[$locale] as $codeKey=>$code)
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="code_{{$codeKey}}_{{$locale}}" class="col-sm-4 control-label">{{$code}}</label>

                                                    <div class="col-sm-8">                                                        
                                                        {!! Form::text($locale.'[spec]['.$codeKey.']', $spec[$codeKey] ?? null, array('class' => 'form-control' )) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>                                    
                                    <div class="form-group">
                                        <label for="description{{$locale}}" class="col-sm-2 control-label">{{__('Description')}}</label>

                                        <div class="col-sm-10">
                                            {!! Form::textarea($locale.'[description]', $auto->contents()->where('locale', $locale)->first()->description ?? null, array('placeholder' => __('Description'),'class' => 'form-control', 'rows' => '5', 'id' => 'cke-description-'.$locale )) !!}
                                        </div>
                                    </div>
                                                
                                </div>
                            <!-- /.tab-pane -->                            
                            @endforeach
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
                                        
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('articles.index') }}">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                </div><!-- /.box-footer -->
            
            </div><!-- /.box -->
        {!! Form::close() !!}
       
    </div>
</div>

@include('admin.modals.img_upload')
@include('admin.modals.img_galery_upload')

@endsection

@push('styles')

@endpush

@push('scripts') 
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
    <script>
        @foreach($locales['avalible'] as $locale)
            CKEDITOR.replace( 'cke-description-{{$locale}}' );
        @endforeach
    </script>
    
    <script>
        $(function () {  
            
            $(".select2-brands").select2({
                tags: true,
                tokenSeparators: [',']
            });    
            $(".select2-model").select2({
                tags: true,
                tokenSeparators: [',']
            });

            $(document).on('click','.jq-img-galery-remove', function (e) {
                e.preventDefault();
                var el = $(this);
                var path = el.attr('data-img_remove')
                
                $.post('{{route('image.remove.post')}}', {path: path, type: 'car', sub: {{$auto->id}}, _token: $("input[name=_token]").val() })
                    .done(function (data) {
                        el.closest('.jq-img-galery-element').remove();                        
                        if(path == $('.js-img-set-val').val()){
                            clearMainLogo(); 
                        }
                        if(path == $('#js-related_target-logo').attr('data-path')){                            
                            clearMainLogoSave(); 
                        }
                    });
            });
            
            function clearMainLogo(){
                $('.js-img-logo').find('img').attr('src','{{asset('/img/blank_flag.gif')}}');
                $('.js-img-set-val').val('');
            }
            
            function clearMainLogoSave(){
                $.get('{{url('admin/cars_remove_logo')}}', {id: {{$auto->id}}, _token: $("input[name=_token]").val() });
            }
                    
            $(document).on('change','#price',function (){                
                $(this).val(checkDec($(this).val()));      
            });
            
            $(document).on('keyup','#price',function (){                
                $(this).val(checkDec($(this).val(), 1));      
            });
            
            $(document).on('change','#range',function (){                
                $(this).val(checkDec($(this).val(), 1));      
            });
            
            $(document).on('keyup','#range',function (){                
                $(this).val(checkDec($(this).val(), 1));      
            });
          
            $(document).on('change','.js-friendly-name',function (){                
                $(this).closest('.tab-pane').find('.js-friendly-url').val(slugify($(this).val()));      
            });

            $(document).on('keyup','.js-friendly-name',function (){                
                $(this).closest('.tab-pane').find('.js-friendly-url').val(slugify($(this).val()));      
            });
        
        });
    </script>
@endpush