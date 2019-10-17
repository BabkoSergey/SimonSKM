@extends('admin.layouts.app')

@section('htmlheader_title') {{__('Main Settings')}} @endsection

@section('content')

<div class="row">
    <div class="col-md-12 jq_start_main">
        @if($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> {{__('Success')}}!</h4>
                {{ $message }}   
            </div>
        @endif
        
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-warning"></i> {{__('Error')}}!</h4>
                    {{ $error }}   
                </div>
            @endforeach                        
        @endif
        
        <!-- Custom Tabs (Pulled to the right) -->
        <div class="nav-tabs-custom js-main-tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#general" data-toggle="tab">{{__('General')}}</a></li>                
                <li><a href="#autos" data-toggle="tab">{{__('Auto Params')}}</a></li>                                
                <li><a href="#services" data-toggle="tab">{{__('Services')}}</a></li>                                
                <li><a href="#mail" data-toggle="tab">{{__('Mail')}}</a></li>                                
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="general">                    
                    <!-- form start -->                            
                        {!! Form::model($settings['general'], ['method' => 'PATCH','route' => ['general.update', 'client'], 'class' => 'form-horizontal', 'id'=>'general_form']) !!}
                            <div class="box-body">

                                <div class="form-group">
                                    <label for="general_title" class="col-sm-2 control-label">{{__('Title')}}</label>

                                    <div class="col-sm-10">
                                        @foreach($languages as $lang)
                                            <div class="input-group {{ $langDef != $lang ? 'margin-b-10' : '' }}">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default">
                                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$lang}}" alt="{{$lang}}" />                                                        
                                                    </button>
                                                </div>
                                                <!-- /btn-group -->
                                                {!! Form::text('general-title-'.$lang, $settings['general']['general-title-'.$lang] ?? null, array('placeholder' => __('Title'),'class' => 'form-control')) !!}
                                            </div>     
                                            @if($langDef == $lang)
                                                <!--<span class="help-block text-center">{{__('The default locale. The value will be displayed for all languages, unless otherwise specified.')}}</span>-->
                                            @endif
                                            
                                        @endforeach    
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="general_logo" class="col-sm-2 control-label">{{__('Site logo')}}</label>

                                    <div class="col-sm-10">
                                        <div class="input-group js-related_target" id="js-related_target-logo">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default img-logo js-img-logo">
                                                    <img src="{{$settings['general']['general-logo'] ?? asset('/img/blank_flag.gif')}}">
                                                </button>
                                            </div>
                                            <!-- /btn-group -->
                                            {!! Form::text('general-logo', $settings['general']['general-logo'] ?? null, array('placeholder' => __('Select/Upload file or set absolute Url path'),'class' => 'form-control js-img-set-val')) !!}
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-img-set" data-path_type="main"><i class="fa fa-upload"></i></button>
                                            </span>                                
                                        </div>     
                                        <span class="help-block text-center">{{__('Select/Upload file or set absolute Url path')}}</span>
                                    </div>
                                </div>    
                                
                                <div class="form-group">
                                    <label for="general_email" class="col-sm-2 control-label">{{__('E-mail')}}</label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default img-logo">
                                                    <i class="fa fa-envelope"></i>
                                                </button>
                                            </div>
                                            <!-- /btn-group -->
                                            {!! Form::email('general-email', $settings['general']['general-email'] ?? null, array('placeholder' => __('E-mail'),'class' => 'form-control')) !!}                                            
                                        </div>                                             
                                    </div>
                                </div>    
                                
                                <div class="form-group">
                                    <label for="general_phones" class="col-sm-2 control-label">{{__('Phones')}}</label>

                                    <div class="col-sm-10">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default img-logo">
                                                    <i class="fa fa-phone"></i>
                                                </button>
                                            </div>
                                            <!-- /btn-group -->
                                            {!! Form::select('general-phones[]', $settings['general']['general-phones'] ?? '', $settings['general']['general-phones'] ?? '', array('class' => 'form-control select2-phones', 'multiple')) !!}
                                        </div>                                             
                                    </div>
                                </div>  
                                
                                <div class="form-group">
                                    <label for="general_hours" class="col-sm-2 control-label">{{__('Working hours')}}</label>

                                    <div class="col-sm-10">
                                        @foreach($languages as $lang)
                                            <div class="input-group {{ $langDef != $lang ? 'margin-b-10' : '' }}">
                                                <div class="input-group-btn vertical-top">
                                                    <button type="button" class="btn btn-default">
                                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$lang}}" alt="{{$lang}}" />                                                        
                                                    </button>
                                                </div>
                                                <!-- /btn-group -->
                                                {!! Form::textarea('general-hours-'.$lang, $settings['general']['general-hours-'.$lang] ?? null, array('placeholder' => __('Working hours'),'class' => 'form-control', 'rows'=> '3')) !!}                            
                                            </div>     
                                            @if($langDef == $lang)
                                                <!--<span class="help-block text-center">{{__('The default locale. The value will be displayed for all languages, unless otherwise specified.')}}</span>-->
                                            @endif
                                            
                                        @endforeach                                        
                                    </div>
                                </div>  
                                
                                <div class="form-group">
                                    <label for="general_address" class="col-sm-2 control-label">{{__('Address')}}</label>

                                    <div class="col-sm-10">
                                        @foreach($languages as $lang)
                                            <div class="input-group {{ $langDef != $lang ? 'margin-b-10' : '' }}">
                                                <div class="input-group-btn vertical-top">
                                                    <button type="button" class="btn btn-default">
                                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$lang}}" alt="{{$lang}}" />                                                        
                                                    </button>
                                                </div>
                                                <!-- /btn-group -->
                                                {!! Form::textarea('general-address-'.$lang, $settings['general']['general-address-'.$lang] ?? null, array('placeholder' => __('Address'),'class' => 'form-control', 'rows'=> '3')) !!}                            
                                            </div>     
                                            @if($langDef == $lang)
                                                <!--<span class="help-block text-center">{{__('The default locale. The value will be displayed for all languages, unless otherwise specified.')}}</span>-->
                                            @endif
                                            
                                        @endforeach                                        
                                    </div>
                                </div>
                              
                                
                                <div class="form-group">
                                    <label for="general_description" class="col-sm-2 control-label">{{__('Description')}}</label>

                                    <div class="col-sm-10">
                                        @foreach($languages as $lang)
                                            <div class="input-group {{ $langDef != $lang ? 'margin-b-10' : '' }}">
                                                <div class="input-group-btn vertical-top">
                                                    <button type="button" class="btn btn-default">
                                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$lang}}" alt="{{$lang}}" />                                                        
                                                    </button>
                                                </div>
                                                <!-- /btn-group -->
                                                {!! Form::textarea('general-description-'.$lang, $settings['general']['general-description-'.$lang] ?? null, array('placeholder' => __('Description'),'class' => 'form-control', 'rows'=> '4')) !!}                            
                                            </div>     
                                            @if($langDef == $lang)
                                                <!--<span class="help-block text-center">{{__('The default locale. The value will be displayed for all languages, unless otherwise specified.')}}</span>-->
                                            @endif
                                            
                                        @endforeach                                        
                                    </div>
                                </div>
                                
                            </div><!-- /.box-body -->            

                            <div class="box-footer">
                                <a class="btn btn-default" role="button" href="{{ route('general.index') }}">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                            </div><!-- /.box-footer -->

                        {!! Form::close() !!}
                    
                </div>
                <!-- /.tab-pane -->                
                
                <div class="tab-pane" id="autos">                    
                    <!-- form start -->                            
                        {!! Form::model($settings['autos'], ['method' => 'PATCH','route' => ['general.update', 'client'], 'class' => 'form-horizontal', 'id'=>'autos_form']) !!}
                            <div class="box-header">
                                <h3 class="box-title">{{__('Car parameters')}}</h3>
                                <div class="box-tools pull-right">                                    
                                    <a class="btn btn-success margin-l-10" role="button" data-toggle="modal" data-target="#modal-auto-key"> {{ __('Add New') }}</a>                                    
                                </div>
                            </div>
                            <div class="box-body jq-auto-code-block">                                
                                @foreach($settings['autos'][$langDef] as $code=>$val)                                
                                    <div class="form-group">
                                        <label for="code" class="col-sm-2 control-label jq-auto-code" data-code="{{$code}}">{{$code}}:</label>
                                        <div class="col-sm-9">
                                            @foreach($languages as $lang)
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <div class="input-group-btn">
                                                            <button type="button" class="btn btn-default">
                                                                <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$lang}}" alt="{{$lang}}" />                                                        
                                                            </button>
                                                        </div>
                                                        <!-- /btn-group -->
                                                        {!! Form::text('autos-'.$code.'-'.$lang, $settings['autos'][$lang][$code] ?? null, array('placeholder' => __(''),'class' => 'form-control', 'required')) !!}
                                                    </div>     
                                                </div>
                                            @endforeach    
                                        </div>
                                        <div class="col-sm-1">
                                            <button class="btn btn-danger pull-right auto-setting-row-delete" data-code="{{$code}}" type="button"><i class="fa fa-trash"></i></button>                                            
                                        </div>
                                    </div>
                                @endforeach    
                            </div><!-- /.box-body -->            
                            
                            <div class="box-footer">
                                <a class="btn btn-default" role="button" href="{{ route('general.index') }}">{{ __('Cancel') }}</a>
                                <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                            </div><!-- /.box-footer -->

                        {!! Form::close() !!}
                        
                        <form method="POST" action="{{ url('/admin/settings/general'). '/autos-'}}" id="auto-setting-form-delete" accept-charset="UTF-8">
                            @csrf
                            <input name="_method" type="hidden" value="DELETE">                                                                                
                        </form>
                        
                        <div id="jq-auto-code-block-template" style="display: none;">                                                                
                            <div class="form-group">
                                <label for="code" class="col-sm-2 control-label jq-auto-code" data-code=""></label>
                                <div class="col-sm-9">
                                    @foreach($languages as $lang)
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default">
                                                    <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$lang}}" alt="{{$lang}}" />                                                        
                                                </button>
                                            </div>
                                            <!-- /btn-group -->
                                            <input placeholder="" class="form-control" required="" name="" type="text" value="">                                                        
                                        </div>     
                                    </div>
                                    @endforeach    
                                </div>
                                <div class="col-sm-1">
                                    <a class="btn btn-danger pull-right row-delete" role="button" href=""><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    
                </div>
                <!-- /.tab-pane -->
                
                <div class="tab-pane" id="services">
                    
                </div>
                <!-- /.tab-pane -->
                
                <div class="tab-pane" id="mail">                    
                    <!-- form start -->                            
                    {!! Form::model($settings['mail'], ['method' => 'PATCH','route' => ['general.update', 'server'], 'class' => 'form-horizontal', 'id'=>'mail_form']) !!}
                        <div class="box-body">

                            <div class="form-group">
                                <label for="mail_driver" class="col-sm-2 control-label">{{__('Mail protocol')}}</label>

                                <div class="col-sm-10">                                        
                                    {!! Form::text('mail_driver', $settings['mail']['mail_driver'] ?? null, array('placeholder' => __('Mail protocol'),'class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mail_host" class="col-sm-2 control-label">{{__('Mail host')}}</label>

                                <div class="col-sm-10">                                        
                                    {!! Form::text('mail_host', $settings['mail']['mail_host'] ?? null, array('placeholder' => __('Mail host'),'class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mail_port" class="col-sm-2 control-label">{{__('Mail port')}}</label>

                                <div class="col-sm-10">                                        
                                    {!! Form::text('mail_port', $settings['mail']['mail_port'] ?? null, array('placeholder' => __('Mail port'),'class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mail_username" class="col-sm-2 control-label">{{__('Mail login')}}</label>

                                <div class="col-sm-10">                                        
                                    {!! Form::text('mail_username', $settings['mail']['mail_username'] ?? null, array('placeholder' => __('Mail login'),'class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mail_password" class="col-sm-2 control-label">{{__('Mail password')}}</label>

                                <div class="col-sm-10">                                        
                                    {!! Form::password('mail_password', array('placeholder' => __('Mail protocol'),'class' => 'form-control')) !!}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="mail_encryption" class="col-sm-2 control-label">{{__('Mail encryption')}}</label>

                                <div class="col-sm-10">                                        
                                    {!! Form::text('mail_encryption', $settings['mail']['mail_encryption'] ?? null, array('placeholder' => __('Mail encryption'),'class' => 'form-control')) !!}
                                </div>
                            </div>
                    
                                
                        </div><!-- /.box-body -->            

                        <div class="box-footer">
                            <a class="btn btn-default" role="button" href="{{ route('general.index') }}">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                        </div><!-- /.box-footer -->

                    {!! Form::close() !!}
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
                          
    </div>
</div>

@include('admin.modals.img_upload')
@include('admin.modals.auto_new_key')

@endsection

@push('styles')

@endpush

@push('scripts')    
<script>
    $(document).ready(function () {
        var hashes = ['autos', 'general', 'services', 'mail'];
        
        if(typeof window.location.hash != "undefined"){
            var hash = window.location.hash.replace(/#/gi, ''); 
            if(hashes.indexOf(hash) != -1){
                $('.js-main-tabs li, .tab-pane').removeClass('active');
                $('#'+hash).addClass('active');
                $('.js-main-tabs li').each(function(){                    
                    if($(this).find('a').attr('href') === '#'+hash) $(this).addClass('active');
                });
            }            
        }
        
        $(document).on('click','.js-main-tabs li', function(e){
            window.location.hash = $(this).find('a').attr('href');
        });
                    
        $(".select2-phones").select2({
            tags: true,
            tokenSeparators: [',']
        });
        
        $(document).on('click','.row-delete', function(e){
            e.preventDefault();            
            $(this).closest('.form-group').remove();
        });
                    
        $(document).on('click','.auto-setting-row-delete', function(e){
            e.preventDefault();           
            $('#auto-setting-form-delete').attr('action',$('#auto-setting-form-delete').attr('action')+$(this).attr('data-code')+'#autos').submit();            
        });
    });
                    
</script>
@endpush