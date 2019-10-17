@extends('layouts.app')

{{-- @section('htmlheader_title') {{ __('Symon SKM') }} @endsection --}}

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')

    <section class='not-found'>
        <div class="not-found-bg"></div>
        <div class="banner-content wrapper">

            <a class="banner-content-button" href="{{ URL::previous() }}">
                <div class="main-button-container">
                    <div class="main-button"><img src="{{ asset('assets/Path.svg')}}" alt=""></div>
                    <div class="main-button-hover"><span class="text-button-hover">{{ __('!_to previos page') }}</span></div>
                </div>
            </a>
            <div class="banner-content-header">
                <p>{{ __('!_Oops') }},</p> {{ __('!_something went wrong') }} <div>404</div> {{ __('!_page is not found') }}
            </div>
            <a class="banner-content-button" href="{{ url($locale) }}">
                <div class="main-button-container">
                    <div class="main-button"><img src="{{ asset('assets/Path.svg')}}" alt=""></div>
                    <div class="main-button-hover"><span class="text-button-hover">{{ __('!_to main page') }}</span></div>
                </div>
            </a>
        </div>
    </section>

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush