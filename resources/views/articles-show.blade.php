@extends('layouts.app')

@section('htmlheader_title') {{ $article->title ?? $article->name }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')
    <div>
        <div class="article-page">
            <div class="article-page-banner">
                <div class="wrapper">
                    <h4 class="content-header">{{ $article->name ?? $article->title }}</h4>
                    <div class="article-page-banner-img">
                        <img src="{{ asset($article->article->logo) }}" alt="" />
                    </div>
                </div>
            </div>
            
             <div class="wrapper"><p class='article-page-date'>{{ date('d M Y',strtotime($article->updated_at)) }}</p></div>    
            
            <div class="article-page-content">                
                {!! $article->content !!}                
            </div>
            
        </div>
    </div> 

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush