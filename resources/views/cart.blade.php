@extends('layouts.app')

@section('htmlheader_title') {{ __('!_Shopping cart') }} @endsection

@section('top_light') {{false}} @endsection

@section('footer') {{true}} @endsection

@section('content')
    
    <div class="shopping-cart page-content">
        <h4 class="content-header">Shopping cart</h4>
        <form method="POST" action="{{ url($locale.'/order_create') }}" id="form-cart" autocomplete="off"  accept-charset="UTF-8">        
            @csrf
            <input type="hidden" name="dates" val="" required/>
                        
            <div class="shopping-cart-form wrapper jq-info-wrapper">
                <div class="form-group">
                    <input type="text" name="name" class="form-group-input" placeholder="name" value="{{$cart['name'] ?? ''}}" readonly required />            
                    <img src="{{ asset('assets/pencil_edit.svg')}}" alt="" class="form-group-img"/>
                    <span style="display: none; cursor: pointer;">&#10004;</span>
                </div>                   
                <div class="form-group">
                    <input type="email" name="email" class="form-group-input" placeholder="email" value="{{$cart['email'] ?? ''}}" readonly required />
                    <img src="{{ asset('assets/pencil_edit.svg')}}" alt="" class="form-group-img" />
                    <span style="display: none; cursor: pointer;">&#10004;</span>
                </div>
                <div class="form-group">
                    <input type="text" name="phone" class="form-group-input" placeholder="phone" value="{{$cart['phone'] ?? ''}}" readonly required />
                    <img src="{{ asset('assets/pencil_edit.svg')}}" alt="" class="form-group-img" />
                    <span style="display: none; cursor: pointer;">&#10004;</span>
                </div>
            </div>
            
            <div class="shopping-cart-list">
                <div class="wrapper">
                    
                </div>
            </div>
            <div id="tpl" style="display: none;">
                <div class="shopping-cart-item">
                    <div class="shopping-cart-item-content">{{ __('!_Date') }}:</div>
                    <div class="shopping-cart-item-content-text jq-tpl-date"></div>
                    <div class="shopping-cart-item-content">{{ __('!_Price') }}:</div>
                    <div class="shopping-cart-item-content-text jq-tpl-price"></div>
                    <div class="shopping-cart-item-img jq-tpl-remove"><img src="{{ asset('assets/delete.png')}}" alt="" /></div>                    
                </div>
            </div>
            
            <div class="shopping-cart-info">
                <div class="shopping-cart-info-list">
                    {{ __('!_Choose your method of payment') }}:
                    <div class="shopping-cart-info-checkbox-group">
                        <input type="radio" hidden id='checkbox-card' name='checkbox-shoppping-cart' value="visa" disabled />
                        <label for='checkbox-card'>
                            <img src="{{ asset('assets/visa.png')}}" alt="" />
                            <img src="{{ asset('assets/mastercard.png')}}" alt="" />
                        </label>
                    </div>
<!--                    <div class="shopping-cart-info-checkbox-group">
                        <input type="radio" hidden id='checkbox-pay' name='checkbox-shoppping-cart' value="paypal" disabled />
                        <label for='checkbox-pay'>
                            <img src="{{ asset('assets/paypal.png')}}" alt="" />
                        </label>
                    </div>-->

                    <div class="shopping-cart-info-checkbox-group">
                        <input type="radio" hidden id='checkbox-cash' name='checkbox-shoppping-cart' value="cash" checked />
                        <label for='checkbox-cash'>{{ __('!_cash')}}</label>
                    </div>
                </div>
                <div class='contacts-form-checkbox-group'>
                    <input id='contacts-form-checkbox' type="checkbox" name="conditions" hidden></input>
                    <label for='contacts-form-checkbox'>
                        <a href="{{url($locale.'/terms')}}" class='contacts-form-checkbox-group'>{{ __('!_ With your order, you agree to our terms and conditions and cancellation policy.') }}</a>
                    </label>
                </div>
                <div class='shopping-cart-info-total'>
                    <div class='shopping-cart-info-total-price'>{{ __('!_total price') }} 
                        <span class='shopping-cart-info-total-num'></span>
                    </div>
                    <div class='shopping-cart-form-submit jq-submite'> {{ __('!_confirm')}}
                        <div class="button">
                            <img src="{{ asset('assets/Path.svg')}}" alt="" />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
@endsection

@push('styles')
    <style>
        .error{
            border-bottom: solid 1px red!important;
        }           
    </style>
@endpush

@push('scripts')
    
    <script src="{{ asset('/plugins/moment/min/moment.min.js') }}"></script>    
     
    <script>
        $(function () { 
            $( document ).ready(function() {
                        
                var preEvents =  @json($resrved);     
                var price = @json($trailer['price'] ?? 0);
                
                var preSetEventsStr =  @json($cart['dates'] ?? '');
                var preSetEvents = preSetEventsStr != '' ? preSetEventsStr.split(';') : [];
                
                var formateDateDisabled = 'YYYY-MM-DD';                
                var naArray = [];
                $.each( preEvents , function( key, value ) {     
                    var result = dateFns.eachDay(moment(value.start, formateDateDisabled), moment(value.end, formateDateDisabled));
                    if(result.length > 1){
                        $.each( result , function( key, day ) {                           
                            naArray.push(moment(day).format(formateDateDisabled));                                                        
                        });
                    }else{                    
                        naArray.push(moment(value.start, formateDateDisabled).format(formateDateDisabled));
                    } 
                });
                
                var selectedDays = [];

                $.each( preSetEvents , function( key, value ) {     
                    if(!isDateDisabled(moment(value, formateDateDisabled))){                        
                        selectedDay(value);
                    }
                    
                });
                                
                function isDateDisabled(date){                
                    return naArray.indexOf(date.format(formateDateDisabled)) > -1;
                }
            
                function selectedDay(date, remove=false){  
                    
                    if(isDateDisabled(moment(date, formateDateDisabled)))
                        return false;
                    
                    if(remove){
                        selectedDays.splice(selectedDays.indexOf(date), 1);
                    }else{
                        selectedDays.push(date);
                    }
                    
                    $('.shopping-cart-info-total-num').text(selectedDays.length*price + ' €');
                    
                    renderCart();
                }
                                
                function renderCart(){                    
                    
                    selectedDays.sort();
                    
                    $('.shopping-cart-list .wrapper').html('');
                    $.each( selectedDays , function( key, value ) {                            
                        var clone = $('#tpl');                          
                        clone.find('.jq-tpl-date').attr('data-date',value).text('{'+value+'}');
                        clone.find('.jq-tpl-price').attr('data-price',value).text('{'+price+'}');                                                
                        $('.shopping-cart-list .wrapper').append(clone.html());                        
                    });
                    
                    $('input[name=dates]').val(selectedDays.length > 0 ? selectedDays.join(';') : '');
                }
                
                $(document).on('click','.jq-tpl-remove', function(){
                    var item = $(this).closest('.shopping-cart-item');
                    var date = item.find('.jq-tpl-date').attr('data-date');
                    
                    item.remove();
                    
                    selectedDay(date, true);
                });
                
                $(document).on('click','.jq-info-wrapper img', function(){
                    $(this).hide();
                    $(this).closest('.form-group').find('input').prop('readonly', false);
                    $(this).closest('.form-group').find('span').show();
                });
                
                $(document).on('click','.jq-info-wrapper span', function(){
                    $(this).hide();
                    $(this).closest('.form-group').find('input').prop('readonly', true);
                    $(this).closest('.form-group').find('img').show();
                    $('#form-cart input').removeClass('error');
                });
                        
                $(document).on('click','.jq-submite', function(){
                    $('#form-cart input').removeClass('error');
                    if(checkValidForm()){console.log('aaaaa');
                        $('#form-cart').submit();
                    }                    
                });
                $(document).on('change keyup','#form-cart input', function(){
                    $('#form-cart input').removeClass('error');
                });
                
                function checkValidForm(){
                    var valid = true;
                    var errorMessage = '';
                    
                    if(!$('input[name=dates]').val()){
                        errorMessage += ' Empty select dates!';
                        valid = false;
                    }
                    
                    if(!$('input[name=conditions]').prop('checked')){
                        errorMessage += ' {{ __('!_ With your order, you agree to our terms and conditions and cancellation policy.') }}';
                        valid = false;
                    }
                    
                    $('#form-cart .jq-info-wrapper input').each(function(){
                        if( !$(this).val() || ( $(this).attr('type') == 'email' && !emailValid($(this).val()) ) || !$(this).prop('readonly') ){
                            $(this).addClass('error');
                            valid = false;
                        }                                               
                    });
                    
                    if(errorMessage != '')
                        alert(errorMessage);
                    return valid;
                }
                
            });
        });
                
    </script>
@endpush