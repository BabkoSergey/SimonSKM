@extends('layouts.app')

@section('htmlheader_title') {{ $page->content->title ?? $page->content->name }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')
    <div>
        <div class=" policy-page">
            <div class="article-page-banner">
                <div class="wrapper">
                    <h4 class="content-header">{{ $page->content->name ?? $page->content->title }}</h4>
                </div>
            </div>

            <a href="{{ URL::previous() }}" class="policy-page-btn article-item-content-link">
                <div class="button">
                    <img src="{{ asset('assets/Path.svg')}}" alt="" />
                </div>
            </a>

            <div class="article-page-content">
                <p class="article-page-content-text">
                    {!! $page->content->content !!}
                </p>
            </div>

        </div>
    </div> 

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush