@extends('admin.layouts.app')

@section('htmlheader_title') {{ ($category->contents()->where('locale', $locales['locale'])->first() ?? $category->contents()->where('locale', $locales['def'])->first())->name }} @endsection

@section('sub_title') {{ __('View') }} @endsection

@section('content')

<div class="row">

    <div class="col-md-3">

        <!-- Info Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">{{__('Common parameters')}}</h3>
                <p class="pull-right margin-r-30 text-{{$category->status ? 'success' : 'danger'}}">
                    <i class="fa fa-{{$category->status ? 'eye' : 'ban'}}">&nbsp;</i>
                    <span class="">{{$category->status ? __('Active') : __('Disabled')}}</span>                                        
                </p>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>

            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive" src="{{$category->logo ?? asset('/img/blank_flag.gif')}}" alt="{{__('Service logo')}}">

                @foreach($locales['avalible'] as $locale)
                    <h3 class="profile-username">                    
                        <img src="{{asset('/img/blank_flag.gif')}}" class="margin-r-10 flag flag-{{$locale}}" alt="{{$locale}}" />
                        <a href="{{ url('/service/') . ($category->contents()->where('locale', $locale)->first() ?? $category->contents()->where('locale', $locales['def'])->first())->url }}" target="_blank">
                            {{ ($category->contents()->where('locale', $locale)->first() ?? $category->contents()->where('locale', $locales['def'])->first())->name }}
                        </a>
                    </h3>
                @endforeach                                

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <b>{{__('Parent')}}</b> 
                        <a class="pull-right" href="{{ $category->parent ? route('art_categorys.show', ['id' => $category->parent]) : '' }}">
                            {{ $category->parent ? ( ($category->nearestParent->contents()->where('locale', $locales['locale'])->first() ?? $category->nearestParent->contents()->where('locale', $locales['def'])->first())->name) : null }}                            
                        </a>
                    </li>                                                    
                
                    <li class="list-group-item">
                        <b>{{__('Articles')}}</b> 
                        <a class="pull-right">
                            {{ $category->articles->count() }}
                        </a>
                    </li>                                                    
                </ul>

            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        
        <div class="box box-primary">
                        
            <div class="box-header">
                <h3 class="box-title">{{__('SEO')}}</h3>
                
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">                               
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
                                        {{$category->contents()->where('locale', $locale)->first()->title ?? null}}
                                    </p>                                    
                                    <p>
                                        <strong>{{__('Friendly URL')}} : </strong>
                                        {{$category->contents()->where('locale', $locale)->first()->url ?? null}}
                                    </p>                                    
                                    <p>
                                        <strong>{{__('Description')}} : </strong>
                                        {{$category->contents()->where('locale', $locale)->first()->description ?? null}}
                                    </p>                                    
                                    <p>
                                        <strong>{{__('Meta')}} : </strong>
                                        {{$category->contents()->where('locale', $locale)->first()->meta ?? null}}
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
        </div>
        <!-- /.box -->
        
    </div>
    <!-- /.col -->
    <div class="col-md-9">
        <div class="box box-primary">
                        
            <div class="box-header">
                <h3 class="box-title">{{__('Articles')}}</h3>
                
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.box-header -->

            <div class="box-body">            
                @foreach($category->articles as $article)                
                    <div class="post clearfix">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" src="{{$article->logo ?? asset('/img/blank_flag.gif')}}">
                            <span class="username">
                                <a href="{{ route('articles.show', ['id' => $article->id]) }}">
                                    {{ ($article->contents()->where('locale', $locales['locale'])->first() ?? $article->contents()->where('locale', $locales['def'])->first())->name }}
                                </a>                                
                            </span>
                            <span class="description text-{{$article->status ? 'success' : 'danger'}}">
                                <i class="fa fa-{{$article->status ? 'eye text-success' : 'ban text-danger'}}">&nbsp;</i>
                                <span class="text-{{$article->status ? 'success' : 'danger'}}">{{$article->status ? __('Active') : __('Disabled')}}</span>                                        
                            </span>                            
                
                        </div>
                        <!-- /.user-block -->
                        <p>
                            {{ mb_strimwidth(strip_tags( ($article->contents()->where('locale', $locales['locale'])->first() ?? $article->contents()->where('locale', $locales['def'])->first())->content ), 0, 500, "...") }}
                        </p>

                    </div>                
                @endforeach
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