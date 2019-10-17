@extends('admin.layouts.app')

@section('htmlheader_title') {{__('Trailers')}} @endsection

@section('content')

<div class="row">
    <div class="col-md-12 jq_start_main">
        
       @include('admin.templates.action_notifi')
        
        <!-- Custom Tabs (Pulled to the right) -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                @foreach($trailers as $trailer)                    
                <li @if ($loop->first) class="active" @endif ><a href="#trailer-{{$trailer->id}}" data-toggle="tab"><b>{{$trailer->name}}</b></a></li>                                    
                @endforeach
                
                @if(Auth::user()->hasPermissionTo('add trailers'))
                    <!--<a class="btn btn-success margin-r-10 margin-t-5 pull-right" role="button" href="{{ route('trailers.create') }}"> {{ __('Add New Trailer') }}</a>-->
                @endif
            </ul>
            <div class="tab-content">
                @foreach($trailers as $trailer)                                        
                    <div class="tab-pane {{$loop->first ? 'active' : ''}}" id="trailer-{{$trailer->id}}">  
                        <div class="row">
                            <div class="col-sm-6">

                                <div class="box box-primary">
                                    <div class="box-header">
                                        <div class="pull-left">   
                                            @if(Auth::user()->hasPermissionTo('delete trailers'))
                <!--                                <form method="POST" action="{{ route('trailers.destroy', ['id'=>$trailer->id]) }}" class="pull-right" accept-charset="UTF-8">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">                                    
                                                    <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i></button>                                
                                                </form>                                -->
                                            @endif

                                            @if(Auth::user()->hasPermissionTo('edit trailers'))
                                                <a href="{{ route('trailers.edit', ['id'=>$trailer->id]) }}" class="btn btn-primary pull-right">                                
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            @endif
                                            
                                            <h4 class="margin-r-10" style="float: left;">{{__('Common parameters')}}</h4>  
                                            
                                        </div>
                                        
                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.box-tools -->

                                    </div>
                                    
                                    <div class="box-body box-profile">
                                        <h4>{{ __('Price') }}: {{$trailer->price}}</h4>                                                                
                                        {!! $trailer->description !!}
                                    </div>
                                    
                                </div>
                                <!-- /.box-header -->

                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">{{__('Gallery')}}</h3>

                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>

                                    <div class="box-body box-profile">
                                        <div class="col-sm-12" style="display: flex;flex-wrap: wrap;" id="jq-img-galery-block">
                                            @foreach($trailer->images['images'] as $key=>$image)
                                                <div class="col-sm-3 jq-img-galery-element" >
                                                    <img src="{{$image}}" style="width: 90%;"/>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">

                                    </div>
                                </div>
                                <!-- /.box -->

                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">{{ __('Description') }}</h3>

                                        <div class="box-tools pull-right">
                                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <!-- /.box-tools -->
                                    </div>

                                    <div class="box-body box-profile">
                                        <div class="nav-tabs-custom inside-box">
                                                <ul class="nav nav-tabs">
                                                    @foreach($locales['avalible'] as $locale)
                                                        <li class="{{ $locale == $locales['def'] ? 'active' : '' }}">
                                                            <a href="#tab_{{$locale}}-spec" data-toggle="tab">
                                                                <img src="{{asset('/img/blank_flag.gif')}}" class="flag flag-{{$locale}}" alt="{{$locale}}" />                                        
                                                                {{ $locale == $locales['def'] ? '*' : '' }}
                                                            </a>
                                                        </li>                                               
                                                    @endforeach
                                                </ul>

                                                <div class="tab-content">
                                                    @foreach($locales['avalible'] as $locale)
                                                        @php $spec = $trailer->contents()->where('locale', $locale)->first() ? $trailer->contents()->where('locale', $locale)->first()->spec ? unserialize($trailer->contents()->where('locale', $locale)->first()->spec) : [] : []; @endphp                                                        
                                                        <div class="tab-pane {{ $locale == $locales['def'] ? 'active' : '' }}" id="tab_{{$locale}}-spec">
                                                            <div class="row">                                                                      
                                                                @foreach($spec as $codeKey=>$code)
                                                                    <div class="col-sm-12">                                                                        
                                                                        <span> <b>{{$code['code']}}: </b></span>
                                                                        <span> {{$code['val']}}</span>                                                                            
                                                                    </div>
                                                                @endforeach                                                                                
                                                            </div>         

                                                        </div>
                                                    <!-- /.tab-pane -->                            
                                                    @endforeach
                                                </div>
                                                <!-- /.tab-content -->
                                            </div>
                                            <!-- nav-tabs-custom -->
                                    </div>
                                    <!-- /.box-body -->

                                    <div class="box-footer">

                                    </div>
                                </div>
                                <!-- /.box -->                                                    

                            </div>

                            <div class="col-sm-6">

                                <div class="box box-primary">

                                    <div class="box-header">
                                        <h3 class="box-title">
                                            <strong>{{ __('Disabled dates') }}: </strong>
                                        </h3>

                                        @if(Auth::user()->hasPermissionTo('disable trailers'))
                                            <a href="{{ url('admin/trailers/disables/'.$trailer->id.'/create') }}" class="btn btn-success margin-t-5 pull-right">{{ __('Add New') }}</a>
                                        @endif
                                    </div>
                                    <!-- /.box-header -->

                                    <div class="box-body">
                                        <table id="trailers-table" class="table table-bordered table-striped tabel-mass-actions">
                                            <thead>
                                                <tr>                                        
                                                    <th>{{__('From')}}</th>
                                                    <th>{{__('To')}}</th>
                                                    <th>{{__('Description')}}</th>  
                                                    @if(Auth::user()->hasPermissionTo('disable trailers'))
                                                        <th style="width: 105px;">{{__('Actions')}}</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>                                    
                                                @foreach(array_reverse($trailer->disables->toArray()) as $disables)                                  
                                                    <tr>
                                                        <td>{{substr($disables['from'], 0, strpos($disables['from'], ' '))}}</td>
                                                        <td>{{substr($disables['to'], 0, strpos($disables['to'], ' '))}}</td>
                                                        <td>{{$disables['description']}}</td>
                                                        @if(Auth::user()->hasPermissionTo('disable trailers'))
                                                            <td>
                                                                <a href="{{ url('admin/trailers/disables/'.$trailer->id.'/'.$disables['id'].'/edit')}}" class="btn btn-primary margin-r-10"><i class="fa fa-pencil"></i></a>
                                                                <form method="POST" action="{{ url('admin/trailers/disables') }}/{{$trailer->id}}/{{$disables['id']}}/delete" class="pull-right" accept-charset="UTF-8">
                                                                    @csrf                                                            
                                                                    <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i></button>                                
                                                                </form>                                                        
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach                                    
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>{{__('From')}}</th>
                                                    <th>{{__('To')}}</th>
                                                    <th>{{__('Description')}}</th>                        
                                                    @if(Auth::user()->hasPermissionTo('disable trailers'))
                                                        <th>{{__('Actions')}}</th>
                                                    @endif
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div><!-- /.box-body -->            

                                    <div class="box-footer">

                                    </div><!-- /.box-footer -->

                                </div><!-- /.box -->                        
                            </div>
                        
                        </div>

                    </div>
                    <!-- /.tab-pane -->
                @endforeach                
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
                          
    </div>
</div>

@endsection

@push('styles')

@endpush

@push('scripts')    
    <script>
        $(function () {  
            
            $('form').submit(function(e) {
                e.preventDefault();
                
                var currentForm = this;
                var mess = "{{__('Are you sure you want to delete a trailer?')}}";
                
                bootbox.confirm({
                    message: mess,
                    buttons: {
                        cancel: {
                            label: "{{__('Cancel')}}",
                            className: 'btn-default pull-left'
                        },
                        confirm: {
                            label: "{{__('Delete')}}",
                            className: 'btn-danger pull-right'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            currentForm.submit();
                        }
                    }                    
                });
            });
            
        });
    </script>
@endpush