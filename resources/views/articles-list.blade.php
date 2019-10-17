@extends('layouts.app')

@section('htmlheader_title') {{ __('!_Blog') }} @endsection

@section('top_light') {{true}} @endsection

@section('footer') {{true}} @endsection

@section('content')

    <div class="banner-pages">
        <img src="{{ asset('assets/banner.png')}}" alt="" />
        <h1 class="banner-pages-header">{{__('!_Blog')}}</h1>
    </div>

    @foreach($articles as $article)
        @if ($loop->first)
            <li class="article-item wrapper">
                <div class="article-item-img" style="background-image: url('{{ asset($article->logo)}}')"></div>
                <div class="article-item-content">
                    <h4 class="content-header">{{ $article->content->name ?? $article->content->title }}</h4>
                    <div class="article-item-content-info">
                        <p class="article-item-content-text">{!! mb_strimwidth(strip_tags($article->content->description ?? $article->content->content), 0, 485, " ...") !!}</p>
                        <a href="{{url($locale.'/blog/'.$article->content->url)}}" class="article-item-content-link">{{ __('!_know more')}}
                            <div class="button">
                                <img src="{{ asset('assets/Path.svg')}}" alt=""/>
                            </div>
                        </a>
                    </div>
                </div>
            </li>
            <div class="blog-list wrapper">
        @else
            <div class="blog-item ">
                <div class="article-item-img" style="background-image: url('{{ asset($article->logo)}}')"></div>
                <div class="article-item-content">
                    <h4 class="content-header">{{ $article->content->name ?? $article->content->title }} </h4>
                    <div class="article-item-content-info">
                        <a href="{{url($locale.'/blog/'.$article->content->url)}}" class="article-item-content-link">
                            <div class="button">
                                <img src="{{ asset('assets/Path.svg')}}" alt=""/>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        @if ($loop->last)
                <div class='blog-iten-empty'></div>
                <div class='blog-iten-empty'></div>
                <div class='blog-iten-empty'></div>
            </div>
        @endif
        
    @endforeach

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush