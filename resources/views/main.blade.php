@extends('layouts.app')

{{-- @section('htmlheader_title') {{ __('Symon SKM') }} @endsection --}}

@section('top_light') {{true}} @endsection

@section('footer') {{false}} @endsection

@section('content')

    <div class="banner  ">
        <div class="banner-bg">
            <div class="banner-bg-img img-left"></div>
            <div class="banner-bg-img img-right"></div>
        </div>
        <div class="banner-content wrapper">
            <a href='{{url($locale.'/caravan_rent')}}' class='banner-content-button clickLeft'>
                <div class='main-button-container'>
                    <div class="main-button">
                        <img src="{{ asset('assets/Path.svg')}}" alt="" />
                    </div>
                    <div class='main-button-hover'>
                        {{ __('!_caravan rent') }}
                    </div>
                </div>

            </a>
            <h1 class="banner-content-header">
                <div>{{ __('!_main caravan') }} </div>
                <div>{{ __('!_main rent') }} </div>
                <div>& </div>
                <div>{{ __('!_car sale') }}</div>
            </h1>
            <a href='{{url($locale.'/cars')}}' class='banner-content-button clickRight'>
                <div class='main-button-container'>
                    <div class="main-button">
                        <img src="{{ asset('assets/Path.svg')}}" alt="" />
                    </div>
                    <div class='main-button-hover'>
                        {{ __('!_car sale') }}
                    </div>
                </div>

            </a>
        </div>
    </div>

@endsection

@push('styles')

@endpush

@push('scripts')

@endpush