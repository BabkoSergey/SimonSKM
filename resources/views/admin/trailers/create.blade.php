@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Trailer') }} @endsection

@section('sub_title') {{ __('Add New') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @include('admin.templates.action_notifi')
        
        <!-- form start -->            
                        
        {!! Form::open(array('method'=>'POST', 'route' => 'trailers.store', 'class' => 'form-horizontal')) !!}
            <!-- Horizontal Form -->
            <div class="box box-info">            
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('Add New trailer')}}</h3>
                </div><!-- /.box-header -->
            
                <div class="box-body">
                                        
                    <div class="form-group">
                        <label for="parent" class="col-sm-2 control-label">{{__('Name')}}*</label>
                        
                        <div class="col-sm-10">                            
                            {!! Form::text('name', null, array('placeholder' => __('Name'),'class' => 'form-control', 'required' )) !!}                            
                        </div>                        
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">{{__('Price')}}</label>

                        <div class="col-sm-10">
                            {!! Form::number('price', 0, array('placeholder' => __('Price'),'class' => 'form-control', 'min'=>0, 'id'=>'price', 'step'=>'0.01')) !!}                                      
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">{{__('Description')}}</label>

                        <div class="col-sm-10">
                            {!! Form::textarea('description', null, array('placeholder' => __('Description'),'class' => 'form-control', 'rows'=> '3', 'id' => 'cke-description' )) !!}                            
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
        });
    </script>
            
@endpush