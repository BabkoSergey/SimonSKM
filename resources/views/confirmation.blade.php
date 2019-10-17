@extends('layouts.app')

@section('htmlheader_title') {{ __('!_Confirmation') }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')

    <div class="page-content confirmation">
        <h4 class="content-header">{{ __('!_Your purchase is confirmed!') }}</h4>
        <div class="shopping-cart-list">
            <div class="wrapper">
                
            </div>
        </div>
        <div id="tpl" style="display: none;">
                <div class="shopping-cart-item">
                    <div class="shopping-cart-item-content">{{ __('!_Date') }}:</div>
                    <div class="shopping-cart-item-content-text jq-tpl-date"></div>
                    <div class="shopping-cart-item-content">{{ __('!_To') }}:</div>
                    <div class="shopping-cart-item-content-text jq-tpl-date-to"></div>
                    <div class="shopping-cart-item-content">{{ __('!_Price') }}:</div>
                    <div class="shopping-cart-item-content-text jq-tpl-price"></div>
                </div>
            </div>
                
        <div class="shopping-cart-info">
            <div class='shopping-cart-info-total'>
                <div class='shopping-cart-info-total-price'>{{ __('!_total price') }} <span class='shopping-cart-info-total-num'></span></div>
                <div class='shopping-cart-form-submit'>
                    <a href="{{url($locale)}}" class="article-item-content-link">
                        <div class="button">
                            <img src="{{ asset('assets/Path.svg')}}" alt="" />
                        </div>
                        {{ __('!_back to main page') }}
                    </a>
                </div>
            </div>
        </div>

    </div>
    
@endsection

@push('styles')
    
@endpush

@push('scripts')
    
    <script src="{{ asset('/plugins/moment/min/moment.min.js') }}"></script>    
     
    <script>
        $(function () { 
            $( document ).ready(function() {
                
                var formateDate = 'YYYY-MM-DD';  
                var formateDateStr = 'YYYY-MM-DD H:i:s';  
                
                var preSetFromStr =  @json($order['from'] ?? ''),
                    preSetToStr =  @json($order['to'] ?? ''),
                    preSetPriceStr =  @json($order['price'] ?? ''),
                    preSetDiscountsStr =  @json($order['discounts'] ?? '');
                    
                var preSetFrom =  preSetFromStr != '' ? preSetFromStr.split(';') : [],
                    preSetTo =  preSetToStr != '' ? preSetToStr.split(';') : [],
                    preSetPrice =  preSetPriceStr != '' ? preSetPriceStr.split(';') : [],
                    preSetDiscounts =  preSetDiscountsStr != '' ? preSetDiscountsStr.split(';') : [];
                
                $.each( preSetFrom , function( key, value ) {     
                    renderCart(moment(value, formateDateStr).format(formateDate), moment(preSetTo[key], formateDateStr).format(formateDate), preSetPrice[key]);
                });
                
                function renderCart(from, to, price){                                                            
                    
                    var clone = $('#tpl');                          
                    
                    clone.find('.jq-tpl-date').text('{'+from+'}');
                    clone.find('.jq-tpl-date-to').text('{'+to+'}');
                    clone.find('.jq-tpl-price').text('{'+price+'}'); 
                    
                    $('.shopping-cart-list .wrapper').append(clone.html());                                            
                }
                
                var total = 0, discounts = 0;                
                $.each( preSetPrice , function( key, value ) {                         
                    total += parseFloat(value);
                });
                $.each( preSetDiscounts , function( key, value ) {     
                    discounts += parseFloat(value);
                });
                
                $('.shopping-cart-info-total-num').text( (total-discounts) + ' €');
                
                if(discounts > 0){
                    var cloneTotal = $('.shopping-cart-info-total-price').clone();                     
                    cloneTotal.html("{{ __('!_discounts') }} <span class='shopping-cart-info-total-num'>"+discounts + " € </span>");
                    $('.shopping-cart-info-total-price').closest('div').prepend(cloneTotal.html());                                            
                }
                                
            });
        });
                
    </script>
@endpush
