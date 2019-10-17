@extends('admin.layouts.app')

@section('htmlheader_title') {{ __('Trailer') }}  @endsection

@section('sub_title') {{ $trailer->name }} @endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        
        @include('admin.templates.action_notifi')
        
        <!-- form start -->                    
        {!! Form::model($disable, ['method' => 'POST','url' => url('admin/trailers/disables/'.$trailer->id.'/'.$disable->id.'/update'), 'class' => 'form-horizontal'] ) !!}        
            <!-- Horizontal Form -->
            <div class="box box-info">            
                <div class="box-header with-border">
                    <h3 class="box-title"> 
                        <h3 class="box-title">{{__('Edit')}} <span class="text-lowercase">{{ __('Disabled dates') }}</span></h3>
                    </h3>
                </div><!-- /.box-header -->
            
                <div class="box-body">                    
                    {!! Form::hidden('trailer_id', $trailer->id, array('required', 'id'=>'trailer_id')) !!}                                                
                    
                     <div class="form-group">
                        <label for="date_time" class="col-sm-2 control-label">{{__('Date and time range')}}*</label>
                        
                        <div class="col-sm-10">
                            {!! Form::hidden('from', $disable->from, array('id' =>'date_from')) !!}                            
                            {!! Form::hidden('to', $disable->to, array('id' =>'date_to')) !!}    
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                {!! Form::text('date_time', null, array('class' => 'form-control', 'required', 'id' =>'date_time')) !!}                                                            
                            </div>                            
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="col-sm-2 control-label">{{__('Description')}}</label>

                        <div class="col-sm-10">
                            {!! Form::text('description', $disable->description, array('placeholder' => __('Description'),'class' => 'form-control' )) !!}                            
                        </div>
                    </div>
                    
                </div><!-- /.box-body -->            
                
                <div class="box-footer">
                    <a class="btn btn-default" role="button" href="{{ route('trailers.index') }}">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-info pull-right">{{ __('Save') }}</button>
                </div><!-- /.box-footer -->
            
            </div><!-- /.box -->
        {!! Form::close() !!}
       
    </div>
</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('/plugins/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@push('scripts') 
    <script src="{{ asset('/plugins/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    
    <script>
        $(function () {  
            
            var formateDate = 'YYYY-MM-DD';
            var formateDateForm = 'YYYY-MM-DD HH:mm:ss';
            var formateDateDisabled = 'YYYY-MM-DD';
            
            var naTimesAll = @json($resrved);            
            var naTimes = naTimesAll[$('#trailer_id').val()];
           
            var preDis = null;
                        
            $.each( naTimes , function( key, value ) {       
                if(value.from == $('#date_from').val()) preDis = key;
                
                value.from = moment(value.from, formateDateForm);
                value.to = moment(value.to, formateDateForm);
            });
            
            if(preDis) naTimes.splice(preDis, 1);
            
            var naArray = [];
            $.each( naTimes , function( key, value ) {             
                var result = dateFns.eachDay(value.from, value.to);
                
                if(result.length > 1){
                    $.each( result , function( key, day ) {                                                   
                        naArray.push(moment(day).format(formateDateDisabled));
                    });                    
                }else{                    
                    naArray.push(value.from.format(formateDateDisabled));
                }             
                
            });
                        
            function setDateLimitDays(date){                
                var isAddDates = false;
                
                $.each( naTimes , function( key, value ) {
                    
                    if(date.isBefore(value.from)){
                        isAddDates = dateFns.differenceInCalendarDays(value.to, date) -1 ; 
                        return false;    
                    }
                    
                });
                
                return isAddDates;
            }
            
            function isDateAvailable(date){                
                return naArray.indexOf(date.format(formateDateDisabled)) > -1;
            }
                    
            $('#date_time').daterangepicker({
                    autoUpdateInput: true,
                    startDate: moment($('#date_from').val(), formateDateForm),
                    endDate: moment($('#date_to').val(), formateDateForm),
                    timePicker: false,                     
                    locale: {
                        format: formateDate
                    },
                    isInvalidDate: isDateAvailable,                    
                    dateLimitDaysSort: true,
                    dateLimitDays: setDateLimitDays                    
                },
                function(start, end, label) {
//                    console.log("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                }
            );
    
            
            $('#date_time').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format(formateDate) + ' - ' + picker.endDate.format(formateDate));
                setFormDates (picker.startDate.format(formateDateForm), picker.endDate.format(formateDateForm));
            });

            $('#date_time').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                setFormDates ('', '');
            });
            
            function setFormDates (from, to) {
                $('#date_from').val(from);
                $('#date_to').val(to);
            }
                                        
        });
    </script>
@endpush