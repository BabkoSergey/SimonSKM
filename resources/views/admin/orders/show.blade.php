@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Order') }}: №{{ __($order->id) }} @endsection

@section('sub_title') {{ __('View') }} @endsection

@section('content')

<section class="invoice">
    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <span class="text-capitalize">
                    {{ __($order->order_status) }}
                    <!--<small>({{ __($order->order_type) }})</small>--> 
                </span>
                <small class="pull-right">{{__('Date')}}: {{$order->created_at}}</small>                
            </h2>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">        
        <div class="col-sm-8 invoice-col">
            To
            <address>
                <strong>{{ $order->user ? ($order->user->first_name || $order->user->last_name ? $order->user->first_name . ' ' . $order->user->last_name : $order->user->name) : '' }}</strong><br>                
                Phone: {{ $order->user->phone ?? '' }}<br>
                Email: {{ $order->user->email ?? '' }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>{{__('Order')}} № {{$order->id}}</b><br>            
            <br>                        
            <b>{{__('Service')}}:</b> {{ $order->order_type == 'payment' ? __('Trailer rental') : __($order->order_type) }}<br>            
            <b>{{__('Description')}}:</b> {{ $order->order_type == 'payment' ? $order->trailer->name : __('Order') . '№ ' . $order->order_parent }}<br>            
            <b>{{__('Transaction')}}:</b> {{ $order->transaction ? $order->transaction : __($order->payment_status) }}
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
        <div class="col-xs-12 table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('From') }}</th>                                                
                        <th>{{ __('To') }}</th>                                                
                        <th>{{ __('Subtotal') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->from as $i=>$from)                        
                        <tr>
                            <td>{{$i}}</td>                            
                            <td>{{ $order->order_type == 'payment' ? substr($from, 0, strpos($from, ' ')) : substr($from, 0, strpos($from, ' ')) }}</td>
                            <td>{{ $order->order_type == 'payment' ? substr($order->to[$i], 0, strpos($order->to[$i], ' ')) : substr($from, 0, strpos($from, ' ')) }}</td>
                            <td>€ {{ $order->price[$i] }}</td>
                        </tr>     
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
            <p class="lead">Payment Method: <strong class="text-capitalize">{{ __($order->payment_type) }}</strong></p>
                        
            <p class="text-muted well well-sm no-shadow text-capitalize" style="margin-top: 10px;">
                <strong>{{ __($order->payment_status) }}</strong>                
            </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">            
            <p class="lead">{{__('Transaction')}}: {{ $order->transaction ? $order->transaction : __($order->payment_status) }} </p>

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">{{ __('Subtotal') }}:</th>
                        <td>€ {{ $order->priceTotal }}</td>
                    </tr>
                    @if($order->discountsTotal > 0)
                    <tr>
                        <th style="width:50%">{{ __('Discounts') }}:</th>
                        <td>€ {{ $order->discountsTotal }}</td>
                    </tr>
                    @endif                    
                    <tr>
                        <th>{{ __('Total')}}:</th>
                        <td>€ {{ ($order->priceTotal - $order->discountsTotal) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
<!--        <div class="col-xs-12">
            <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>            
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                <i class="fa fa-download"></i> Generate PDF
            </button>
        </div>-->
    </div>
</section>

@endsection

@push('styles')

@endpush

@push('scripts') 
    
@endpush