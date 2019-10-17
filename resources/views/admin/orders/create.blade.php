@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Order') }} @endsection

@section('sub_title') {{ __('Add New') }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">        
        @include('admin.templates.action_notifi')
        
        <div class="box box-info">            
            <div class="box-header with-border">
                <h3 class="box-title">{{__('Add New Order')}}</h3>
            </div><!-- /.box-header -->
            
            <!-- form start -->                    
            {!! Form::open(array('method'=>'POST', 'route' => 'orders.store', 'class' => 'form-horizontal', 'id' =>'create_form', 'autocomplete'=>'off')) !!}
            <!-- Horizontal Form -->
                <div class="box-body">
                    
                    {!! Form::select('trailer_id', $trailers, 0, array('class' => 'form-control hidden','single', 'required', 'id'=>'trailer_id')) !!}                                                
                    
                    <div class="form-group">
                        <label for="user_id" class="col-sm-2 control-label">{{__('Customer')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('user_id', $users, null, array('class' => 'form-control','placeholder' => __('...'), 'single', 'required', 'id'=>'user_id')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group hidden">
                        <label for="order_type" class="col-sm-2 control-label">{{__('Order type')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('order_type', $enums['order_type'], 'payment', array('class' => 'form-control','single', 'required', 'id' => 'order_type')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group hidden" style="display: none">
                        <label for="order_parent" class="col-sm-2 control-label">{{__('Order parent')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('order_parent', [], null, array('placeholder' => __('...'),'class' => 'form-control', 'disabled','id' => 'order_parent' )) !!}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_time" class="col-sm-2 control-label">{{__('Price')}}, {{__('Date')}}*</label>                                                
                        <div class="col-sm-10">
                            {!! Form::text('show_price', null, array('id' =>'show_price', 'class' => 'form-control', 'disabled')) !!}
                            {!! Form::hidden('dates', null, array('id' =>'dates', 'class' => 'form-control')) !!}                            
                            <div id='calendar'></div>
                        </div>                        
                    </div>
                                        
                    <div class="form-group hidden">
                        <label for="price" class="col-sm-2 control-label">{{__('Discounts')}}</label>
                        
                        <div class="col-sm-10">
                            {!! Form::number('discounts', 0, array('placeholder' => __('Discounts'),'class' => 'form-control', 'min'=>0, 'id'=>'price', 'step'=>'0.01')) !!}                            
                        </div>
                    </div>
                                                            
                    <div class="form-group">
                        <label for="order_status" class="col-sm-2 control-label">{{__('Status')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('order_status', $enums['order_status'], 'new', array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_type" class="col-sm-2 control-label">{{__('Payment type')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('payment_type', $enums['payment_type'], 'online', array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="payment_status" class="col-sm-2 control-label">{{__('Payment status')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::select('payment_status', $enums['payment_status'], 'not paid', array('class' => 'form-control','single', 'required')) !!}                            
                        </div>
                    </div>
                    
                </div><!-- /.box-body -->            
            {!! Form::close() !!}   
            
            <div class="box-footer">
                <a class="btn btn-default" role="button" href="{{ route('orders.index') }}">{{ __('Cancel') }}</a>
                <button type="submit" class="btn btn-info pull-right jq-submite-form">{{ __('Save') }}</button>
            </div><!-- /.box-footer -->
            
        </div><!-- /.box -->
               
    </div>
</div>

@endsection

@push('styles')
<!--    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">-->
    <link href="{{ asset('/plugins/fullcalendar-4.0.2/packages/core/main.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/fullcalendar-4.0.2/packages/daygrid/main.css') }}" rel="stylesheet">
    
@endpush

@push('scripts')  
<!--    <script src="{{ asset('/plugins/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>    -->
    
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/core/main.js') }}"></script>
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/interaction/main.js') }}"></script>
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/daygrid/main.js') }}"></script>
    <script src="{{ asset('/plugins/moment/min/moment.min.js') }}"></script>   
    
    <script>
        $(function () {  
            $( document ).ready(function() {
            
                var selectedColor = 'green';
                var disabledColor = '#e63131';

                var preEvents =  @json($resrved);                 
                var prePrice = @json($prices);
                var price = prePrice[$('#trailer_id').val()];
                
                var formateDateDisabled = 'YYYY-MM-DD';
                var formateDateOrder = 'YYYY-MM-DD H:i:s';
                var Events = [];
                var naArray = [];
                
                var preSetEvents = [];
                
                $.each( preEvents[$('#trailer_id').val()] , function( key, value ) {     
                    if(!isDateSelected(moment(value.start, formateDateDisabled))){
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
                        defaultDate: '2019-04-01',
                        editable: false,
                        eventLimit: true,                    
                        selectable: false,
                        events: Events,
                        contentHeight: 375,
                        header: {                    
                            left: '',
                            right: 'prev,next today',
                            center: 'title'
                        },                       
                        dateClick: function(info) {                           
                            selectedDay(info.dateStr);
                        }                    
                });

                calendar.render();
                $.each( preSetEvents , function( key, value ) {     
                    if(!isDateDisabled(moment(value, formateDateDisabled))){                        
                        selectedDay(value);
                    }

                });


                function isDateSelected(date){
                    return preSetEvents.indexOf(date.format(formateDateDisabled)) > -1;
                }
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
                    $('input[name=dates]').val(selectedDays.length > 0 ? selectedDays.join(';') : '');
                    $('input[name=show_price]').val(selectedDays.length*price + '');
                }
                    
                    $(document).on('click','.jq-submite-form', function(){                        
                        if(!$('input[name=dates]').val()){
                            alert('Select dates!');
                            return false;
                        }
                        
                        if(!$('select[name=user_id]').val()){
                            alert('Customer not set!');
                            return false;
                        }
                                                    
                        $('#create_form').submit();
                    });  
//            
//            const overlay = '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>';
//            const typePayment = 'payment';
//            var order_parent = $('#order_parent');
//            var order_type = $('#order_type');
//            var formateDate = 'YYYY-MM-DD';
//            var formateDateForm = 'YYYY-MM-DD HH:mm:ss';
//            var formateDateDisabled = 'YYYY-MM-DD';
//            
//            var naTimesAll = @json($resrved);            
//            var naTimes = naTimesAll[$('#trailer_id').val()];
//            
//            $.each( naTimes , function( key, value ) {            
//                value.from = moment(value.from, formateDateForm);
//                value.to = moment(value.to, formateDateForm);
//            });
//            
//            var naArray = [];
//            $.each( naTimes , function( key, value ) {             
//                var result = dateFns.eachDay(value.from, value.to);
//                
//                if(result.length > 1){
//                    $.each( result , function( key, day ) {                           
//                        naArray.push(moment(day).format(formateDateDisabled));
//                    });                    
//                }else{                    
//                    naArray.push(value.from.format(formateDateDisabled));
//                }             
//                
//            });
//                                    
//            function setDateLimitDays(date){                
//                var isAddDates = false;
//                
//                $.each( naTimes , function( key, value ) {                    
//                    if(date.isBefore(value.from)){
//                        isAddDates = dateFns.differenceInCalendarDays(value.to, date) -1 ; 
//                        return false;    
//                    }
//                    
//                });
//                
//                return isAddDates;
//            }
//            
//            function isDateAvailable(date){                
//                return naArray.indexOf(date.format(formateDateDisabled)) > -1;
//            }
//                    
//                    
//            $('#date_time').daterangepicker({
//                    autoUpdateInput: false,
//                    minDate:moment(),
//                    timePicker: false,                     
//                    locale: {
//                        format: formateDate
//                    },
//                    isInvalidDate: isDateAvailable,                    
//                    dateLimitDaysSort: true,
//                    dateLimitDays: setDateLimitDays,                    
//                },
//                function(start, end, label) {
////                    console.log("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
//                }
//            );
//    
//            
//            $('#date_time').on('apply.daterangepicker', function(ev, picker) {
//                $(this).val(picker.startDate.format(formateDate) + ' - ' + picker.endDate.format(formateDate));
//                setFormDates (picker.startDate.format(formateDateForm), picker.endDate.format(formateDateForm));
//            });
//
//            $('#date_time').on('cancel.daterangepicker', function(ev, picker) {
//                $(this).val('');
//                setFormDates ('', '');
//            });
//            
//            function setFormDates (from, to) {
//                $('#date_from').val(from);
//                $('#date_to').val(to);
//            }
//            
//            $(document).on('change','#price, #discounts',function (){                
//                $(this).val(checkDec($(this).val()));      
//            });
//            
//            $(document).on('keyup','#price, #discounts',function (){                
//                $(this).val(checkDec($(this).val()));      
//            });
//            
//            $(document).on('change','#user_id',function (){ 
//                $(this).closest('.form-group').removeClass('has-error');
//                                
//                ( order_type.val() == typePayment || !$('#user_id').val() ) ? closeAvalibleOrders() : refreshAvalibleOrders();
//            });
//            
//            $(document).on('change','#order_type',function (){ 
//                
//                $('#user_id').closest('.form-group').removeClass('has-error');
//                
//                if($(this).val() == typePayment){                    
//                    closeAvalibleOrders();
//                }else{
//                    preSetFormData();
//                    if(!$('#user_id').val()){
//                        $('#user_id').closest('.form-group').addClass('has-error');
//                        $(this).val(typePayment);                        
//                        return false;
//                    }                                                                                              
//                    refreshAvalibleOrders();
//                }
//                
//            });
//            
//            function preSetFormData(){
//                $('#date_time').val(moment().format(formateDateForm), moment().add(1, 'seconds').format(formateDateForm));
//                $('#date_time').closest('.form-group').hide();
//                setFormDates (moment().format(formateDateForm), moment().add(1, 'seconds').format(formateDateForm));
//            }
//            
//            function closeAvalibleOrders(){
//                $('#date_time').val('');
//                $('#date_time').closest('.form-group').show();
//                setFormDates ('', '');                
//                order_type.val(typePayment);                        
//                order_parent.val('');  
//                order_parent.html('');
//                order_parent.prop('disabled', true);
//                order_parent.closest('.form-group').hide();
//                order_parent.closest('.form-group').find('.overlay').remove();
//            }
//            
//            function refreshAvalibleOrders(){
//                order_parent.prop('disabled', true);
//                order_parent.val('');                    
//                order_parent.html('');
//                $(overlay).insertBefore(order_parent);
//                order_parent.closest('.form-group').show();
//                
//                    
//                $.get('{{url('admin')}}/orders/'+$('#user_id').val()+'/users', {_token: $("input[name=_token]").val()})
//                        .done(function (data) {                            
//                            order_parent.closest('.form-group').find('.overlay').remove();                    
//                            if(data.success == 'ok'){                                  
//                                if(data.orders[$('#user_id').val()].length != 0){
//                                    order_parent.append('<option value="">...</option>');                                    
//                                    $.each( data.orders[$('#user_id').val()], function( key, v ) {
//                                        order_parent.append('<option value="'+v.id+'">#'+v.id+' '+v.order_type+' ('+v.order_status+') total:'+(v.price - v.discounts)+' transaction:'+ v.payment_status+' '+v.transaction+'</option>');                                    
//                                    });  
//                                    order_parent.prop('disabled', false);
//                                }
//                            }                    
//                        })
//                        .fail(function (data) {
//                            order_parent.closest('.form-group').find('.overlay').remove();
//                        });                
//            }
            });
        });
    </script>
@endpush