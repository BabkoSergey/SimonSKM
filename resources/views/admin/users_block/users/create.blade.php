@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Users') }} @endsection

@section('sub_title') {{ __('Add new') }} @endsection

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
        

        <!-- Horizontal Form -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{__('Add New User')}}</h3>
            </div><!-- /.box-header -->
            <!-- form start -->            
                        
            {!! Form::open(array('method'=>'POST', 'route' => 'users.store', 'class' => 'form-horizontal')) !!}
                <div class="box-body">
                    
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{__('User name')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">{{__('User E-mail')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::email('email', null, array('placeholder' => 'E-mail','class' => 'form-control', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="col-sm-2 control-label">{{__('Password')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::password('password', array('placeholder' => '','class' => 'form-control', 'required')) !!}                                       
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm-password" class="col-sm-2 control-label">{{__('Confirm password')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::password('confirm-password', array('placeholder' => '','class' => 'form-control', 'required')) !!}                                       
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="first_name" class="col-sm-2 control-label">{{__('First name')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::text('first_name', null, array('placeholder' => 'First Name','class' => 'form-control')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="col-sm-2 control-label">{{__('Last name')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::text('last_name', null, array('placeholder' => 'Last Name','class' => 'form-control')) !!}                            
                        </div>
                    </div>
                                        
                    <div class="form-group">
                        <label for="phone" class="col-sm-2 control-label">{{__('Phone')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::text('phone', null, array('placeholder' => 'XXXXXXXXXXX','class' => 'form-control')) !!}                            
                        </div>
                    </div>
                                                            
                    <div class="form-group">
                        <label for="roles" class="col-sm-2 control-label">{{__('Roles')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('roles[]', $roles,[], array('class' => 'form-control select2', 'multiple')) !!}                            
                        </div>
                    </div>                    
                    
                    <div class="form-group">
                        <label for="status" class="col-sm-2 control-label">{{__('Status')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('status', array(0=>'Fired', 1=>'Active'), 1, array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                                        
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                </div><!-- /.box-footer -->
                                
            {!! Form::close() !!}
            
        </div><!-- /.box -->
       
    </div>
</div>


@endsection

@push('styles')
    
@endpush

@push('scripts')    
    
    <script>
      $(function () {  
          $('.select2').select2();
          
      });
    </script>
            
@endpush