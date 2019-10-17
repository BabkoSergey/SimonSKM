@extends('admin.layouts.app')

@section('htmlheader_title') {{$user->name}} @endsection

@section('sub_title') {{ __('Edit') }} @endsection

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
                    <h4><i class="icon fa fa-warning"></i> Error!</h4>
                    {{ $error }}   
                </div>
            @endforeach                        
        @endif
        
        <!-- Custom Tabs (Pulled to the right) -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#main_info" data-toggle="tab">{{__('Main info')}}</a></li>                
                <li><a href="#password" data-toggle="tab">{{__('Password')}}</a></li>                                
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="main_info">
                    <!-- form start -->            
                    {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id], 'class' => 'form-horizontal', 'id'=>'main_form']) !!}
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
                                <label for="roles" class="col-sm-2 control-label">{{__('Roles')}}*</label>

                                <div class="col-sm-10">
                                    {!! Form::select('roles[]', $roles,$user->roles->pluck('name', 'name'), array('class' => 'form-control select2', 'multiple')) !!}                            
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
                </div>
                <!-- /.tab-pane -->
                
                <div class="tab-pane" id="password">
                    <!-- form start -->            
                    {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id], 'class' => 'form-horizontal', 'id'=>'password_form']) !!}
                        <div class="box-body">

                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">{{__('New Password')}}*</label>

                                <div class="col-sm-10">
                                    {!! Form::password('password', array('placeholder' => '','class' => 'form-control', 'required')) !!}                                       
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirm-password" class="col-sm-2 control-label">{{__('Confirm New Password')}}*</label>

                                <div class="col-sm-10">
                                    {!! Form::password('confirm-password', array('placeholder' => '','class' => 'form-control', 'required')) !!}                                       
                                </div>
                            </div>

                        </div><!-- /.box-body -->            

                        <div class="box-footer">
                            <a class="btn btn-default" role="button" href="{{ route('users.index') }}">{{ __('Cancel') }}</a>
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


@endsection

@push('styles')

@endpush

@push('scripts')    
    <script>
        $(function () {  
            $('.select2').select2();
            
            $(document).on('submit','#main_form, #password_form',function (e){
                e.preventDefault(); 
                
                $('.alert').remove();
                
                $.post($(this).attr('action'), $(this).serialize())
                    .done(function(data) { 
                        $('.jq_start_main').prepend('<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Success!</h4> Updated successfully!</div>');
                    })
                    .fail(function(error) { 
                        $.each( error.responseJSON.errors, function( type, obj ) {                            
                            $.each( obj, function( key, error ) {
                                $('.jq_start_main').prepend('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-warning"></i> Error!</h4>'+error+'</div>');
                            }); 
                        }); 
                    });
            });
                                  
        });
    </script>
@endpush