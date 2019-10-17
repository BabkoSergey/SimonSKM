@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Dashboard') }} @endsection

{{--@section('sub_title') {{ __('VIEW') }} @endsection--}}

{{--@section('breadcrumb') TRUE @endsection--}}

@section('content')
    
    <!-- Info boxes -->
      <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">{{__('Cars for sale')}}</span>
              <span class="info-box-number">{{$infoBoxes['cars']}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col --> 

      </div>
      <!-- /.row -->

    <div class="row">
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> 
                        <span class="text-capitalize">{{ __('Rent calendar') }}</span>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div id='calendar'></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        
        <div class="col-md-6 col-sm-12 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> 
                        <span class="text-capitalize">{{ __('Last orders') }}</span>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @foreach($orders as $order)                
                        <section class="invoice">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">
                                        <span class="text-capitalize">
                                            <a href="{{ route('orders.show',['id'=> $order->id]) }}">
                                                <b>{{__('Order')}} № {{$order->id}}</b>                                            
                                            </a>
                                        </span>
                                        <span class="lead">
                                            <small>Payment Method:</small>
                                            <strong class="text-capitalize"><small>{{ __($order->payment_type) }}</small></strong>                                        
                                            <small class="text-capitalize">({{ __($order->payment_status) }})</small>
                                        </span>
                                        
                                        <small class="pull-right">{{__('Date')}}: {{$order->created_at}}</small>
                                    </h2>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">        
                                <div class="col-sm-8 invoice-col">                                    
                                    <address>
                                        <strong>{{ $order->user ? ($order->user->first_name || $order->user->last_name ? $order->user->first_name . ' ' . $order->user->last_name : $order->user->name) : '' }}</strong><br>                
                                        Phone: {{ $order->user->phone ?? '' }}<br>
                                        Email: {{ $order->user->email ?? '' }}
                                    </address>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 invoice-col">
                                    <address>
                                        Total: <strong>€ {{ $order->price }}</strong><br> 
                                        @foreach($order->from as $key=>$date)
                                            <div>
                                                <b>From:</b> {{ substr($date, 0, strpos($date, ' ')) }}
                                                <b>to:</b> {{ substr($order->to[$key], 0, strpos($order->to[$key], ' ')) }}
                                            </div>
                                        @endforeach                                        
                                    </address>                                                                        
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            
                        </section>                
                    @endforeach
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->    
      </div>
        
@endsection

@push('styles')    
    <link href="{{ asset('/plugins/fullcalendar-4.0.2/packages/core/main.css') }}" rel="stylesheet">
    <link href="{{ asset('/plugins/fullcalendar-4.0.2/packages/daygrid/main.css') }}" rel="stylesheet">
    <style>
        .invoice{
            padding: 0;
            margin: 0;
        }
        #calendar {          
          margin: 0 auto;
        }
        .calendar-event{
            font-size: 1.2em;
            padding: 0.8em;
        }
        .calendar-event-type-new{
            background-color: #3788d8;
            border-color: #23598e;
        }
        .calendar-event-type-processed{
            background-color: #8bd698;
            border-color: #6da877;
        }
        .calendar-event-type-closed{
            background-color: #095b18;
            border-color: #053a0f;
        }
   
      </style>
@endpush

@push('scripts')    
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/core/main.js') }}"></script>
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/interaction/main.js') }}"></script>
    <script src="{{ asset('/plugins/fullcalendar-4.0.2/packages/daygrid/main.js') }}"></script>
    
    <script>
        
        $(function () { 
            $( document ).ready(function() {
                
                var Events =  @json($resrved ?? []),
                    calendarEl = document.getElementById('calendar');
                    
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    plugins: [ 'interaction', 'dayGrid' ],
                    defaultDate: '2019-04-01',
                    editable: false,
                    eventLimit: false,
                    events: Events,
                    contentHeight: 550
                });
                
                calendar.render();
        
            });
        });
        
    </script>

@endpush