@extends('layouts.app')

@section('htmlheader_title') {{ __('!_Caravan rent') }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')

<div class='page-content rent'>
    
    <div class="rent-info">
        <div class="wrapper">
            <div class="car-page-info">
                <div class="car-page-info-portfolio ">
                    <div class=" slider-for">
                        @forelse($gallery as $image)
                            <img src="{{$image}}"  alt="{{ $trailerInfo->name }}"/>    
                        @empty
                            <img src="{{ asset($trailerInfo->logo)}}"  alt="{{ $trailerInfo->name }}"/>    
                        @endforelse                         
                    </div>
                </div>
                <div class="car-page-info-about">
                    <ul class='car-content-info-list'>
                        @foreach($trailerInfo->spec as $row)
                            <li class="car-content-info-list-item">
                                <span class="car-content-info-list-item-category">{{$row['code']}}</span>
                                <span class="car-content-info-list-item-text">{{$row['val']}}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <h4 class="content-header">{{ __('!_Choose a date to rent a caravan') }}</h4>

    <div class="rent-content">
        <div id='calendar'></div>
        
        <form method="POST" action="{{ url($locale.'/cart_update') }}" class='rent-contacts' id="form-rent-contacts" autocomplete="off" accept-charset="UTF-8">  
            @csrf
            <input type="hidden" name="dates" val="">            
            <div>
                <h5 class='rent-btn-text'>{{ __('!_Enter your contact info') }} </h5>
                <div class='rent-contacts-group'>
                    <input type="text" name="name" class="contacts-content-form-input" placeholder="name" value="{{$cart['name'] ?? ''}}" required >
                    <input type="email" name="email" class="contacts-content-form-input" placeholder="email" value="{{$cart['email'] ?? ''}}" required >
                    <input type="tel" name="phone" class="contacts-content-form-input" placeholder="phone" value="{{$cart['phone'] ?? ''}}" required >
                </div>
                <div class='contacts-form-checkbox-group'>
                    <input id='contacts-form-checkbox' type="checkbox" name='conditions' hidden></input>
                    <label for='contacts-form-checkbox'>
                        <a href="{{url($locale.'/terms')}}" class='contacts-form-checkbox-group'>{{ __('!_ With your order, you agree to our terms and conditions and cancellation policy.') }}</a>
                    </label>
                </div>
            </div>
            <button style="display: none" class="jq-submite-form"></button>
        </form>
    </div>

    <div class="shopping-cart-list">
        <h5 class="shopping-cart-list-header">{{ __('!_Booking data') }}</h5>

        <div class="wrapper">
            <div class='rent-cart'></div>
            <button type='button' class="rent-btn jq-submite">
                <div class='rent-btn-text'>{{ __('!_continue') }}</div>
                <div class="button">
                    <img src="{{ asset('assets/Path.svg')}}" alt="" />
                </div>
            </button>
        </div>
    </div>

    <div id="tpl" style="display: none;">
        <div class="shopping-cart-item">
            <div class="shopping-cart-item-content">{{ __('!_Date') }}:</div>
            <div class="shopping-cart-item-content-text jq-tpl-date">{date}</div>
            <div class="shopping-cart-item-content">{{ __('!_Price') }}:</div>
            <div class="shopping-cart-item-content-text jq-tpl-price">{price}</div>
            <div class="shopping-cart-item-img jq-tpl-remove"><img src="{{ asset('assets/delete.png')}}" alt="" /></div>                
        </div>
    </div>
</div>

@endsection

@push('styles')
    <link href="{{ asset('/plugins/fullcalendar-4.0.2/packages/core/main.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/fullcalendar-4.0.2/packages/daygrid/main.css') }}" rel="stylesheet">
    <style>
        .error{
            border-bottom: solid 1px red;
        }           
    </style>
@endpush

@push('scripts')    
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/core/main.js') }}"></script>
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/interaction/main.js') }}"></script>
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/daygrid/main.js') }}"></script>
    
    <script src="{{ asset('/plugins/moment/min/moment.min.js') }}"></script>  
    
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js"></script>-->
    <script src="{{ asset('js/slick.js') }}"></script> 
    
    <script>
        $(function () { 
            $( document ).ready(function() {
                
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
                
                var setHeight = 700;
                
                var selectedColor = '#e63131';
                var disabledColor = '#ffe517';
                        
                var preEvents =  @json($resrved);     
                var price = @json($trailer['price'] ?? 0);
                
                var preSetEventsStr =  @json($cart['dates'] ?? '');
                var preSetEvents = preSetEventsStr != '' ? preSetEventsStr.split(';') : [];
                
                var formateDateDisabled = 'YYYY-MM-DD';
                var Events = [];
                var naArray = [];
                $.each( preEvents , function( key, value ) {     
                    var result = dateFns.eachDay(moment(value.start, formateDateDisabled), moment(value.end, formateDateDisabled));
                    if(result.length > 1){
                        $.each( result , function( key, day ) {                           
                            naArray.push(moment(day).format(formateDateDisabled));                            
                            Events.push(newEvenrRow(value, day));
                        });

                    }else{                    
                        naArray.push(moment(value.start, formateDateDisabled).format(formateDateDisabled));
                        Events.push(newEvenrRow(value, moment(value.start, formateDateDisabled).format(formateDateDisabled)));
                    } 
                });
                
                function newEvenrRow(value, day){
                    
                    return {
                                classNames: value.classNames,
                                color: disabledColor,
                                end: moment(day).format(formateDateDisabled),
                                id: value.id,
                                rendering: value.rendering,
                                start: moment(day).format(formateDateDisabled),
                                title: value.title,
                                trailer_id: value.trailer_id,
                                type: value.type,
                                url: value.url
                            };
                }                         
                         
                var selectedDays = [];

                var calendarEl = document.getElementById('calendar');                

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: [ 'interaction', 'dayGrid' ],
                    defaultDate: moment().format(formateDateDisabled),
                    editable: false,
                    eventLimit: true,                    
                    selectable: false,
                    events: Events,
                    contentHeight: setHeight,
                    header: {                    
                        left: '',
                        right: 'prev,next today',
                        center: 'title'
                    },                    
                    dateClick: function(info) {                           
                        if (moment(info.dateStr, formateDateDisabled).isBefore(moment())){                            
                            return false;
                        }                        
                        selectedDay(info.dateStr);
                    }, 
                    datesRender: function(info){
                        console.log(info.view.currentStart);
                        var now = new Date(); 
                        
                        var cal_date_string = info.view.currentStart.getMonth()+'/'+info.view.currentStart.getFullYear();
                        var cur_date_string = now.getMonth()+'/'+now.getFullYear();

                        if(cal_date_string == cur_date_string) { 
                            $('.fc-prev-button').hide(); 
                        } else { 
                            $('.fc-prev-button').show(); 
                        }
                    }
                });
                
                calendar.render();
                $.each( preSetEvents , function( key, value ) {     
                    if(!isDateDisabled(moment(value, formateDateDisabled))){                        
                        selectedDay(value);
                    }
                    
                });
                
                $(document).on('click','.jq-tpl-remove', function(){
                    var item = $(this).closest('.shopping-cart-item');
                    var date = item.find('.jq-tpl-date').attr('data-date');
                    
                    item.remove();
                    
                    selectedDay(date);
                });
                
                function isDateDisabled(date){                
                    return naArray.indexOf(date.format(formateDateDisabled)) > -1;
                }
            
                function selectedDay(date, event=null){  
                    
                    if(isDateDisabled(moment(date, formateDateDisabled)))
                        return false;
                    
                    var event = calendar.getEventById(date);
                    
                    if(event){                        
                        selectedDays.splice(selectedDays.indexOf(date), 1);
                        event.remove();                        
                    }else{
                        selectedDays.push(date);
                        calendar.addEvent({
                            classNames: "",
                            color: selectedColor,
                            end: date,
                            id: date,
                            rendering: "background",
                            start: date,
                            title: "Reserved!",
                            trailer_id: "{{ $general['trailerId'] ?? 1 }}",
                            type: "Reserved",
                            url: "",
                        });
                    }

                    renderCart();
                }
                
                function renderCart(){                    
                    
                    selectedDays.sort();
                    
                    $('.rent-cart').find('.shopping-cart-item').remove();
                    $.each( selectedDays , function( key, value ) {                            
                        var clone = $('#tpl');                          
                        clone.find('.jq-tpl-date').attr('data-date',value).text('{'+value+'}');
                        clone.find('.jq-tpl-price').attr('data-price',value).text('{'+price+'}'); 
                        $('.rent-cart').append(clone.html());
                    });
                    
                    $('input[name=dates]').val(selectedDays.length > 0 ? selectedDays.join(';') : '');
                }
                
                $(document).on('click','.jq-submite', function(){
                    $('.jq-submite-form').click();
                });
                $(document).on('change keyup','#form-rent-contacts input', function(){
                    $('#form-rent-contacts input').removeClass('error');
                });
                $(document).on('click','.jq-submite-form', function(e){
                    e.preventDefault();
                    var valid = true;
                    if($('input[name=dates]').val()){
                        $('#form-rent-contacts input').each(function(){
                            if(!$(this).val() || ( $(this).attr('type') == 'email' && !emailValid($(this).val())) ){
                                $(this).addClass('error');
                                valid = false;
                            }                            
                        });
                        if(!$('input[name=conditions]').prop('checked')){
                            alert('{{ __('!_ With your order, you agree to our terms and conditions and cancellation policy.') }}');
                            valid = false;
                        }
                        if(valid){
                            $('#form-rent-contacts').submit();
                        }
                    }else{
                        alert('Select dates!');
                    }
                });
                
                
            });
        });
                
    </script>
@endpush