@extends('admin.layouts.app')

@section('htmlheader_title') {{ ($category->contents()->where('locale', $locales['locale'])->first() ?? $category->contents()->where('locale', $locales['def'])->first())->name }} @endsection

@section('sub_title') {{ __('Edit') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> Error!</h4>
                    {{ $error }}   
                </div>
            @endforeach                        
        @endif
        
        <!-- form start -->            

        {!! Form::model($category, ['method' => 'PATCH','route' => ['art_categorys.update', $category->id], 'class' => 'form-horizontal'] ) !!}        
            <!-- Horizontal Form -->
            <div class="box box-info">            
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('Common parameters')}}</h3>
                </div><!-- /.box-header -->
            
                <div class="box-body">
                    <div class="form-group">
                        <label for="logo" class="col-sm-2 control-label">{{__('Category logo')}}</label>

                        <div class="col-sm-10">
                            <div class="input-group js-related_target" id="js-related_target-logo">
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default img-logo js-img-logo">
                                        <img src="{{$category->logo ?? asset('/img/blank_flag.gif')}}">
                                    </button>
                                </div>
                                <!-- /btn-group -->
                                {!! Form::text('logo', $category->logo ?? null, array('placeholder' => __('Select/Upload file or set absolute Url path'),'class' => 'form-control js-img-set-val')) !!}
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-img-set" data-path_type="art_category"><i class="fa fa-upload"></i></button>
                                </span>                                
                            </div>     
                            <span class="help-block text-center">{{__('Select/Upload file or set absolute Url path')}}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="parent" class="col-sm-2 control-label">{{__('Parent Category')}}</label>
                        
                        <div class="col-sm-10">                            
                            {!! Form::select('parent', $categorys, $category->parent, array('class' => 'form-control', 'placeholder' => '...' )) !!}                            
                        </div>                        
                    </div>
                    
                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">{{__('Status')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('status', array(0=> __('Disabled'), 1=> __('Active') ), $category->status ?? 0, array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                                                            
                    <!-- Custom Tabs (Pulled to the right) -->    
                    <div class="box-header with-border margin-b-10">
                        <h3 class="box-title">{{__('Settings')}}</h3>
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
                                    <div class="form-group">
                                        <label for="name_{{$locale}}" class="col-sm-2 control-label">{{__('Name')}} {{ $locale == $locales['def'] ? '*' : '' }}</label>

                                        <div class="col-sm-10">
                                            {!! Form::text($locale.'[name]', $category->contents()->where('locale', $locale)->first()->name ?? null, array('placeholder' => __('Name'),'class' => 'form-control js-friendly-name', ($locale == $locales['def'] ? 'required' : '') )) !!}                            
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="url_{{$locale}}" class="col-sm-2 control-label">{{__('Friendly URL')}} {{ $locale == $locales['def'] ? '*' : '' }}</label>

                                        <div class="col-sm-10">
                                            {!! Form::text($locale.'[url]', $category->contents()->where('locale', $locale)->first()->url ?? null, array('placeholder' => __('Friendly URL'),'class' => 'form-control js-friendly-url', ($locale == $locales['def'] ? 'required' : '') )) !!}                            
                                        </div>
                                        <span class="help-block text-center">{{__('Friendly URL must contain only A-Za-z0-9_- characters!')}}</span>
                                    </div>
                                    
                                    <div class="box-header with-border margin-b-10">
                                        <h3 class="box-title">{{__('SEO')}}</h3>
                                    </div><!-- /.box-header -->
                                            
                                    <div class="form-group">
                                        <label for="title_{{$locale}}" class="col-sm-2 control-label">{{__('Title')}}</label>

                                        <div class="col-sm-10">
                                            {!! Form::text($locale.'[title]', $category->contents()->where('locale', $locale)->first()->title ?? null, array('placeholder' => __('Title'),'class' => 'form-control' )) !!}                            
                                        </div>
                                    </div>
                                            
                                    <div class="form-group">
                                        <label for="description_{{$locale}}" class="col-sm-2 control-label">{{__('Description')}}</label>

                                        <div class="col-sm-10">
                                            {!! Form::textarea($locale.'[description]', $category->contents()->where('locale', $locale)->first()->description ?? null, array('placeholder' => __('Description'),'class' => 'form-control', 'rows'=> '3')) !!}                            
                                        </div>
                                    </div>
                                            
                                    <div class="form-group">
                                        <label for="meta_{{$locale}}" class="col-sm-2 control-label">{{__('Meta')}}</label>

                                        <div class="col-sm-10">
                                            {!! Form::textarea($locale.'[meta]', $category->contents()->where('locale', $locale)->first()->meta ?? null, array('placeholder' => __('Meta'),'class' => 'form-control', 'rows'=> '3')) !!}                            
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
                    <a class="btn btn-default" role="button" href="{{ route('art_categorys.index') }}">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                </div><!-- /.box-footer -->
            
            </div><!-- /.box -->
        {!! Form::close() !!}
       
    </div>
</div>

@include('admin.modals.img_upload')

@endsection

@push('styles')

@endpush

@push('scripts') 
        
    <script>
        $(function () {  
          
                $(document).on('change','.js-friendly-name',function (){                
                     $(this).closest('.tab-pane').find('.js-friendly-url').val(slugify($(this).val()));      
                 });

                 $(document).on('keyup','.js-friendly-name',function (){                
                     $(this).closest('.tab-pane').find('.js-friendly-url').val(slugify($(this).val()));      
                 });
        
        });
    </script>
@endpush