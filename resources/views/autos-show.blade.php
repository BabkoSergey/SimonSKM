@extends('layouts.app')

@section('htmlheader_title') {{ $auto->brand }} {{ $auto->model }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')

    <div class='car-page '>
        <div class="wrapper">
            <div class="car-page-info">
                <div class="car-page-info-portfolio ">
                    <div class=" slider-for">              
                        @forelse($gallery as $image)
                            <img src="{{$image}}"  alt="{{ $auto->brand }} {{ $auto->model }}"/>    
                        @empty
                            <img src="{{ asset($auto->logo)}}"  alt="{{ $auto->brand }} {{ $auto->model }}"/>    
                        @endforelse                        
                    </div>
                </div>
                <div class="car-page-info-about">
                    <div class="car-content">
                        <div class="car-content-header">
                            <span class="car-content-header-brand">{{ $auto->brand }} {{ $auto->model }}</span>
                            <div class="my-rating"></div>
                            <span class="car-content-header-price">{{$auto->price}} $</span>
                        </div>
                        <div class="car-content-info">
                            <ul class="car-content-info-list">
                                <li class="car-content-info-list-item">ID <span
                                        class="car-content-info-list-item-text">{{ $auto->id }}</span></li>
                                <li class="car-content-info-list-item">{{__('!_Release') }} <span
                                        class="car-content-info-list-item-text">{{$auto->release}} {{ __('!_year') }}</span></li>
                                <li class="car-content-info-list-item">{{ __('!_Mileage') }} <span
                                        class="car-content-info-list-item-text">{{$auto->mileage}} {{ __('!_km') }}</span></li>
                            </ul>                            
                        </div>
                    </div>
                </div>
                <div class="car-page-info-button" >
                    <div class="car-content-info-link">
                        {{ __('!_contact us') }}
                        <div class="button" id='open-modal'>
                            <img src="{{ asset('/assets/chat.svg') }}" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal">
            <section class="modal-main">
                <div class="contacts ">
                    <form method="POST" id="contacts-form" class='contacts-modal' action="{{ url('/send_feedback') }}" autocomplete="off"  accept-charset="UTF-8">        
                    @csrf                    
                        <div class='contacts-content'>
                            <h5 class='contacts-header'>{{ __('!_WRITE TO US') }}</h5>

                            <input type="email" name="email" class='contacts-content-form-input' placeholder='e-mail' required />
                            <input type="tel" name="phone" class='contacts-content-form-input' placeholder='phone' required />
                            <input type="text" name="name" class='contacts-content-form-input' placeholder='name' required />
                            <textarea name="message" class='contacts-content-form-textarea' placeholder='message…' required ></textarea>
                
                            <div class="car-content-info-link">
                                {{ __('!_send a message') }}
                                <button type='submit' class='button'>
                                        <img src="{{ asset('/assets/Path.svg') }}" alt="" />
                                </button>
                            </div>

                        </div>
                        <div class='contacts-info'>
                            <h5 class='contacts-header'>{{ __('!_or choose another way to contact us') }}</h5>
                            <ul class='contacts-info-list'>
                                <li class='contacts-info-list-item'>
                                    <div class="button">
                                        <img src="{{ asset('assets/email.svg')}}" alt="" />
                                    </div>
                                    <a href="mailto:{{$general['email']}}"
                                       class='contacts-info-list-item-link'>{{$general['email']}}</a>
                                </li>
                                <li class='contacts-info-list-item'>
                                    <div class="button">
                                        <img src="{{ asset('assets/phone.svg')}}" alt="" />
                                    </div>
                                    @foreach($general['phones'] as $phone)
                                        @if ($loop->first)
                                            <div class="content-phone-list">
                                                <a href="tel:{{str_replace(' ', '', $phone)}}" class='contacts-info-list-item-link'>
                                                    {{$phone}}
                                                </a>
                                            </div>
                                        @endif                                         
                                    @endforeach                                    
                                </li>
                                <div class='contacts-form-checkbox-group'>
                                    <input id='contacts-form-checkbox' type="checkbox" name="conditions" hidden required></input>
                                    <label for='contacts-form-checkbox'>
                                        <a href="{{url($locale.'/terms')}}" class='contacts-form-checkbox-group'>{{ __('!_ With your order, you agree to our terms and conditions and cancellation policy.') }}</a>
                                    </label>
                                </div>
                            </ul>
                        </div>
                        <div class='modal-close'>
                            <div class='modal-close-btn'></div>
                        </div>
                    </form>
                </div>

            </section>
        </div>
    </div>

    <div class="tabs">

        <ul class="tabs__caption">
            <li class="tabs__caption-tab active">{{ __('!_specifications') }}</li>
            <li class="tabs__caption-tab ">{{ __('!_description') }}</li>
        </ul>
        <div class="wrapper">
            <div class="tabs__content active">
                <ul class='car-content-info-list'>                    
                    @foreach($autoParams['params'] as $param)
                        <li class="car-content-info-list-item"><span
                            class="car-content-info-list-item-category">{{ $param['title'] }}</span><span
                            class="car-content-info-list-item-text">{{ $param['val'] }}</span></li>                        
                    @endforeach                 
                </ul>
            </div>
            <div class="tabs__content">
                <p class='car-content-info-list-item-text '>{!! $autoParams['description'] !!}</p>
            </div>
        </div>

    </div>
    <div class="car-viewed wrapper">
        <h4 class="content-header">{{ __('!_Recently viewed') }}</h4>
        <ul class="car-viewed-list"> 
            @foreach($autos as $recentAuto)
                <a href='{{url($locale.'/cars/'.$recentAuto->id)}}' class="car-viewed-list-item car-content-info-link">
                    <div class="car-viewed-list-item-img"><img src='{{ asset($recentAuto->logo)}}' alt="" /></div>
                    <div>
                        <h5 class="car-viewed-list-item-name">{{ $recentAuto->brand }} {{ $recentAuto->model }}</h5>
                        <span class="car-content-info-list-item-text">{{$recentAuto->price}} $</span>
                    </div>
                </a>
            @endforeach
        </ul>
    </div>

@endsection

@push('styles')

@endpush

@push('scripts')
    <script src="{{ asset('js/jquery.star-rating-svg.js') }}"></script>
    <script src="{{ asset('js/slick.js') }}"></script>
    
    <script>
        $(function () { 
            $( document ).ready(function() {
                
                $(".my-rating").starRating({
                    initialRating: {{$auto->range}},
                    activeColor: '#d8c31f',
                    useGradient: false,
                    readOnly: true,
                    starSize: 24,
                    strokeWidth: 0
                });

                $('.slider-for').slick({
                    customPaging : function(slider, i) {                        
                            var thumb = $(slider.$slides[i]).find('img').attr('src');
                            return '<a><img src="'+thumb+'"></a>';
                        },
                    dots: true,
                    arrows: false,
                    dotsClass: "slick-dots slick-thumb",
                    infinite: true,
                    speed: 1000,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    autoplay: true,
                    autoplaySpeed: 2000
                });
                
                $(document).on('submit', '#contacts-form', function (e){
                   e.preventDefault();                   
                   $.ajax({
                        type: 'POST',
                        url: $(this).prop("action"),
                        data: new FormData(this),    
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function () {
                            $('button[type=submit]').prop("disabled", true);
                        },
                        success: function (data) {     
                            $('#contacts-form').trigger("reset");
                            $('button[type=submit]').prop("disabled", false);
                            $(".modal").css('display', 'none');
                        },
                        error: function (data) {
                            $('button[type=submit]').prop("disabled", false);
                        }
                    });
                   
                });
                
            });                  
        });
    </script>
@endpush