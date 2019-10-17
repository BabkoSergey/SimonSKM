@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Car') }} @endsection

@section('sub_title') {{ __('Add New') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @include('admin.templates.action_notifi')
        
        <!-- form start -->            
                        
        {!! Form::open(array('method'=>'POST', 'route' => 'cars.store', 'class' => 'form-horizontal')) !!}
            <!-- Horizontal Form -->
            <div class="box box-info">            
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('Add New Car')}}</h3>
                </div><!-- /.box-header -->
            
                <div class="box-body">
                                        
                    <div class="form-group">
                        <label for="brand" class="col-sm-2 control-label">{{__('Brand')}}*</label>
                        
                        <div class="col-sm-10">                            
                            {!! Form::select('brand', $brands, [], array('class' => 'form-control select2-brands','single', 'placeholder' => '...', 'required' )) !!}
                        </div>                        
                    </div>
                    
                    <div class="form-group">
                        <label for="model" class="col-sm-2 control-label">{{__('Model')}}*</label>
                        
                        <div class="col-sm-10">                            
                            {!! Form::select('model', $models, [], array('class' => 'form-control select2-model','single', 'placeholder' => '...', 'required' )) !!}                            
                        </div>                        
                    </div>
                    
                    <div class="form-group">
                        <label for="release" class="col-sm-2 control-label">{{__('Release year')}}*</label>

                        <div class="col-sm-10">
                            {!! Form::text('release', null, array('placeholder' => __('Release year'),'class' => 'form-control', 'required' )) !!}                            
                        </div>                        
                    </div>
                                        
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('cars.index') }}">{{ __('Cancel') }}</a>
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
          
      });
    </script>
            
@endpush