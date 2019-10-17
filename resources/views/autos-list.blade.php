@extends('layouts.app')

@section('htmlheader_title') {{ __('!_Car sale') }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')

    <div class='page-content'>
        <h4 class="content-header">{{ __('!_Choose a car to buy') }}</h4>
        <div class="page-line-header">
            <div class="wrapper">
                {{ __('!_Chosen filters') }}: 
<!--                <span class='cars-filter-attr'>honda </span><span class='cars-filter-attr'>model name </span><span class='cars-filter-attr'>2005</span>-->
            </div>
        </div>
        <div class="cars-content wrapper jq-content">            
            @include('autos-list-content')            
        </div>
    </div>
    
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/css/ion.rangeSlider.min.css" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.0/js/ion.rangeSlider.min.js"></script>
    
    <script>
                
        $(function () { 
            $( document ).ready(function() {
                
                $(".js-range-slider-mileage").ionRangeSlider(setFilterSlider('mileage'));
                $(".js-range-slider-price").ionRangeSlider(setFilterSlider('price'));
                
                var timerId;
                $( document ).on('change','.jq-filters input', function(){                    
                    clearTimeout(timerId);
                    if($(this).hasClass('jq-ionRangeSlider')){
                        timerId = setTimeout(function() { setFilter(); }, 500);
                        return false;
                    }
                    setFilter();                    
                }); 
                
                function setFilterSlider(type){
                    var input = $('.js-range-slider-'+type);
                    var params = {
                        type:"double",
                        min:input.attr('data-def_min'),
                        max:input.attr('data-def_max'),
                        from:input.attr('data-value_min'),
                        to:input.attr('data-value_max'),
                        prefix: (type == 'price' ? "€" : "")
                    };
                            
                    return params;
                }
                
                function setFilter(){
                    var filter = '';
                    $('.jq-filters .cars-filter-category').each(function(){
                        var values = [];
                        $(this).find('input').each(function(){
                            if($(this).prop('checked')){
                                values.push($(this).attr('data-value'));
                            }
                            if($(this).hasClass('jq-ionRangeSlider')){                                
                                if(parseFloat($(this).val().split(';')[0]) != parseFloat($(this).attr('data-def_min')) || parseFloat($(this).val().split(';')[1]) != parseFloat($(this).attr('data-def_max'))){
                                    values = $(this).val().split(';');
                                }
                            }
                        });
                        if(values.length > 0 ){
                           filter += (filter != '' ? '&' : '')+$(this).attr('data-filter')+'='+values.join(';');
                        }                        
                    }); 
                    
                    getAutos(filter);                    
                }
                
                window.onpopstate = function(event) {
                    var url = new URL(document.location);                    
                    getAutos(url.search.slice(1), false); 
                };
                
                function getAutos(filter, needPushState = true){
                    
                    $.ajax({
                        type: 'GET',
                        url: '{{url($locale.'/cars_filter')}}' + (filter != '' ? '?'+filter : ''),
                        beforeSend: function () {
                            $('.loader').show();
                        },
                        success: function (data) {  
                            setTimeout(function() {                                 
                                $('.jq-content').html(data);
                                $(".js-range-slider-mileage").ionRangeSlider(setFilterSlider('mileage'));
                                $(".js-range-slider-price").ionRangeSlider(setFilterSlider('price')); 
                                
                                if (needPushState) {
                                    var newURL = window.location.protocol + "//" + window.location.host + window.location.pathname + ($('.jq-filters').attr('data-filter_get') != '' ? '?'+$('.jq-filters').attr('data-filter_get').substr(1) : '');
                                    window.history.pushState({path:newURL},'',newURL);
                                }                    
                                
                            }, 300);                            
                        },
                        error: function (data) {
                            $('.loader').hide();
                        }
                    });
                }                
                
            });                  
        });
    </script>
@endpush