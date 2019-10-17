@extends('admin.layouts.app')

@section('htmlheader_title') {{ $user->name }} @endsection

@section('sub_title') {{ __('info') }} @endsection

@section('content')

<div class="row">

    <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="{{Auth::user()->avatar ? Auth::user()->avatar : asset('/img/default-user.png')}}" alt="User profile picture">

                <h3 class="profile-username text-center">{{ $user->first_name||$user->last_name ? $user->first_name.' '.$user->last_name : $user->name }}</h3>

                <p class="text-muted text-center">{{ implode(', ', $user->getRoleNames()->toArray()) }}</p>

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>{{__('Nick Name')}}</b> <a class="pull-right">{{ $user->name }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{__('E-mail')}}</b> <a class="pull-right">{{ $user->email }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>{{__('Phone')}}</b> <a class="pull-right">{{ $user->phone }}</a>
                    </li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->


    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <!-- Custom Tabs (Pulled to the right) -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#permissions" data-toggle="tab">{{__('Permissions')}}</a></li>                
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="permissions">
                    <p>
                        @foreach($user->getAllPermissions() as $permission)
                            <span class="label label-primary">{{ $permission->name }}</span>
                        @endforeach
                    </p>
                </div>
                <!-- /.tab-pane -->
                
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->            

    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@endsection

@push('styles')    

@endpush

@push('scripts')    

@endpush