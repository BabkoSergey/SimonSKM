@extends('layouts.app')

@section('htmlheader_title') {{ $service->title ?? $service->name }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')
    <div>
        <div class="article-page">
            <div class="article-page-banner">
                <div class="wrapper">
                    <h4 class="content-header">{{ $service->name ?? $service->title }}</h4>
                    <div class="article-page-banner-img">
                        <img src="{{ asset($service->service->logo) }}" alt="" />
                    </div>
                </div>
            </div>
            <div class="article-page-content">                
                {!! $service->content !!}                
            </div>
        </div>
    </div> 

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush