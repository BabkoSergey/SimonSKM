@extends('layouts.app')

@section('htmlheader_title') {{ __('!_Services') }} @endsection

@section('top_light') {{true}} @endsection

@section('footer') {{true}} @endsection

@section('content')

    <div class="banner-pages">
        <img src="{{ asset('assets/banner.png')}}" alt="" />
        <h1 class="banner-pages-header">{{__('!_Services')}}</h1>
    </div>

    <ul class="services">
        @foreach($services as $service)
            <li class="article-item wrapper">
                <div class="article-item-img" style="background-image: url('{{ asset($service->logo)}}')"></div>
                <div class="article-item-content">
                    <h4 class="content-header">{{ $service->content->name ?? $service->content->title }}</h4>
                    <div class="article-item-content-info">                        
                        <p class="article-item-content-text">
                            {!! mb_strimwidth(strip_tags($service->content->description ?? $service->content->content), 0, 485, " ...") !!}
                        </p>
                        <a href="{{url($locale.'/services/'.$service->content->url)}}" class="article-item-content-link">{{ __('!_know more')}}
                            <div class="button">
                                <img src="{{ asset('assets/Path.svg')}}" alt=""/>
                            </div>
                        </a>
                    </div>
                </div>
            </li>     
        @endforeach
    </ul>

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush