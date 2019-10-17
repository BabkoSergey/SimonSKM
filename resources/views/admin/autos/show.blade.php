@extends('admin.layouts.app')

@section('htmlheader_title') {{ ($article->contents()->where('locale', $locales['locale'])->first() ?? $article->contents()->where('locale', $locales['def'])->first())->name }} @endsection

@section('sub_title') {{ __('View') }} @endsection

@section('content')

<div class="row">

    <div class="col-md-3">

        <!-- Info Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{__('Common parameters')}}</h3>
                <p class="pull-right margin-r-30 text-{{$article->status ? 'success' : 'danger'}}">
                    <i class="fa fa-{{$article->status ? 'eye' : 'ban'}}">&nbsp;</i>
                    <span class="">{{$article->status ? __('Active') : __('Disabled')}}</span>                                        
                </p>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>

            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive" src="{{$article->logo ?? asset('/img/blank_flag.gif')}}" alt="{{__('Service logo')}}">

                @foreach($locales['avalible'] as $locale)
                    <h3 class="profile-username">                    
                        <img src="{{asset('/img/blank_flag.gif')}}" class="margin-r-10 flag flag-{{$locale}}" alt="{{$locale}}" />
                        <a href="{{ url('/service/') . ($article->contents()->where('locale', $locale)->first() ?? $article->contents()->where('locale', $locales['def'])->first())->url }}" target="_blank">
                            {{ ($article->contents()->where('locale', $locale)->first() ?? $article->contents()->where('locale', $locales['def'])->first())->name }}
                        </a>
                    </h3>
                @endforeach
                                

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>{{__('Category')}}</b> 
                         <a class="pull-right" href="{{ $article->categorys()->first() ? route('art_categorys.show', ['id' => $article->categorys()->first()->id ]) : '' }}">
                            {{ $article->categorys()->first() ? ($article->categorys()->first()->contents()->where('locale', $locales['locale'])->first()->name ?? $article->categorys()->first()->contents()->where('locale', $locales['def'])->first()->name) : null }}
                        </a>
                    </li>                                                    
                </ul>

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        
        <!-- Tree Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{__('SEO')}}</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>

            <div class="box-body box-profile">
                <div class="nav-tabs-custom inside-box">
                        <ul class="nav nav-tabs">
                            @foreach($locales['avalible'] as $locale)
                                <li class="{{ $locale == $locales['def'] ? 'active' : '' }}">
                                    <a href="#tab_{{$locale}}-seo" data-toggle="tab">
                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$locale}}" alt="{{$locale}}" />                                        
                                        {{ $locale == $locales['def'] ? '*' : '' }}
                                    </a>
                                </li>                                               
                            @endforeach
                        </ul>
                        
                        <div class="tab-content">
                            @foreach($locales['avalible'] as $locale)                            
                                <div class="tab-pane {{ $locale == $locales['def'] ? 'active' : '' }}" id="tab_{{$locale}}-seo">
                                    <p>
                                        <strong>{{__('Title')}} : </strong>
                                        {{$article->contents()->where('locale', $locale)->first()->title ?? null}}
                                    </p>                                    
                                    <p>
                                        <strong>{{__('Friendly URL')}} : </strong>
                                        {{$article->contents()->where('locale', $locale)->first()->url ?? null}}
                                    </p>                                    
                                    <p>
                                        <strong>{{__('Description')}} : </strong>
                                        {{$article->contents()->where('locale', $locale)->first()->description ?? null}}
                                    </p>                                    
                                    <p>
                                        <strong>{{__('Meta')}} : </strong>
                                        {{$article->contents()->where('locale', $locale)->first()->meta ?? null}}
                                    </p>   
            
                                </div>
                            <!-- /.tab-pane -->                            
                            @endforeach
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
            </div>
            <!-- /.box-body -->

            <div class="box-footer">

            </div>
        </div>
        <!-- /.box -->

    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="box box-primary">
                        
            <div class="box-header">
                <h3 class="box-title">{{__('Content')}}</h3>
            </div>
            <!-- /.box-header -->

            <div class="box-body">                               
                <div class="nav-tabs-custom inside-box">
                        <ul class="nav nav-tabs">
                            @foreach($locales['avalible'] as $locale)
                                <li class="{{ $locale == $locales['def'] ? 'active' : '' }}">
                                    <a href="#tab_{{$locale}}-content" data-toggle="tab">
                                        <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$locale}}" alt="{{$locale}}" />  
                                        {{ $locale == $locales['def'] ? '*' : '' }}
                                    </a>
                                </li>                                               
                            @endforeach
                        </ul>
                        
                        <div class="tab-content">
                            @foreach($locales['avalible'] as $locale)                            
                                <div class="tab-pane pane-min-500 {{ $locale == $locales['def'] ? 'active' : '' }}" id="tab_{{$locale}}-content">
                                     {!! $article->contents()->where('locale', $locale)->first()->content ?? null !!}           
                                </div>
                            <!-- /.tab-pane -->                            
                            @endforeach
                        </div>
                        <!-- /.tab-content -->
                    </div>
                    <!-- nav-tabs-custom -->
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->

@endsection

@push('styles')

@endpush

@push('scripts') 
    
@endpush