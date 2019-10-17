@extends('layouts.app')

@section('htmlheader_title') {{ __('!_Contacts') }} @endsection

@section('top_light') {{true}} @endsection

@section('footer') {{true}} @endsection

@section('content')
    <div class="banner-pages">
        <img src="{{ asset('assets/banner.png')}}" alt="" />
        <h1 class="banner-pages-header">{{__('!_Contacts')}}</h1>
    </div>

    <div class="contacts wrapper">
        <div class='contacts-content'>
            <h5 class='contacts-header'>{{ __('!_WRITE TO') }}<div> Symon SKM GmbH</div></h5>
            
            <form method="POST" id="contacts-form" class='contacts-content-form' action="{{ url('/send_feedback') }}" autocomplete="off"  accept-charset="UTF-8">        
                @csrf
                <input type="email" name="email" class='contacts-content-form-input' placeholder='e-mail' required />
                <input type="tel" name="phone" class='contacts-content-form-input' placeholder='phone' required />
                <input type="text" name="name" class='contacts-content-form-input' placeholder='name' required />
                <textarea name="message" class='contacts-content-form-textarea' placeholder='message…' required ></textarea>
                <div class='contacts-form-checkbox-group'>
                    <input id='contacts-form-checkbox' name="conditions" type="checkbox" hidden required></input>
                    <label for='contacts-form-checkbox'>
                        <a href="{{url($locale.'/terms')}}" class='contacts-form-checkbox-group'>{{ __('!_ With your order, you agree to our terms and conditions and cancellation policy.') }}</a>
                    </label>
                </div>
                <div class='contacts-form-submit'>
                    {{ __('!_send a message') }}
                    <button type='submit' class='contacts-form-submit-button'>
                            <div class="button">
                                    <img src="{{ asset('assets/Path.svg')}}" alt=""/>
                                </div>
                    </button>
                </div>
            </form>
            
        </div>
        <div class='contacts-info'>
            <h5 class='contacts-header'>{{ __('!_or choose another way to contact us') }}</h5>
            <ul class='contacts-info-list'>
                <li class='contacts-info-list-item'>
                    <div class="button">
                        <img src="{{ asset('assets/email.svg')}}" alt=""/>
                    </div>
                    <a href="mailto:{{$general['email']}}" class='contacts-info-list-item-link'>{{$general['email']}}</a>
                </li>
                <li class='contacts-info-list-item'>
                    <div class="button">
                        <img src="{{ asset('assets/phone.svg')}}" alt=""/>
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
                <li class='contacts-info-list-item'>
                    <div class="button">
                        <img src="{{ asset('assets/placeholder.svg')}}" alt=""/>
                    </div>
                    {{$general['address']}}
                </li>
            </ul>

        </div>
    </div>

@endsection

@push('styles')

@endpush

@push('scripts')
    <script>
        $(function () { 
            $( document ).ready(function() {
                
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