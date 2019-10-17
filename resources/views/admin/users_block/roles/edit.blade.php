@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Roles') }} @endsection

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
        

        <!-- Horizontal Form -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">{{$role->name}}</h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id], 'class' => 'form-horizontal']) !!}
                <div class="box-body">
                    
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{__('Role name')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">{{__('Permissions')}}</label>
                        
                        <div class="col-sm-10">
                            <div class="row padding-t-10">
                                @foreach($permissions as $value)
                                    <div class="col-md-3 col-sm-6">
                                        {{ Form::checkbox('permissions[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                                        {{ $value->name }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('roles.index') }}">{{ __('Cancel') }}</a>
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
 
@endpush