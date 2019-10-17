<form class="cars-filter jq-filters" data-filter_get="{{$filterParamsStr ?? ''}}">
    @foreach($filters as $filter=>$fKeys)
        <div class="cars-filter-category" data-filter="{{$filter}}">
            <h5 class="cars-filter-category-header"> {{ __('!_filter_'.$filter) }}</h5>        
            @if(isset($fKeys['min']) && isset($fKeys['max']))
                <input type="text" class="js-range-slider-{{$filter}} jq-ionRangeSlider" name="my_range" value="" 
                       data-value_min="{{ $filtersSet[$filter]['min'] }}" data-def_min="{{ $fKeys['min'] }}"
                       data-value_max="{{ $filtersSet[$filter]['max'] }}" data-def_max="{{ $fKeys['max'] }}"
                       />                            
            @else                
                @foreach($fKeys as $index=>$key)             
                    <div class="cars-filter-category-checkbox-group" data-filter="{{$filter}}">
                        <input id='cars-form-checkbox_{{$filter}}_{{ $index }}' type="checkbox" data-value="{{ $key }}" hidden {{ in_array($key, $filtersSet[$filter]) ? 'checked' : ''}} ></input>
                        <label for='cars-form-checkbox_{{$filter}}_{{ $index }}' class='checkbox-group-label'>{{ $key }}</label>
                    </div>
                @endforeach                               
            @endif  
        </div>
    @endforeach 
</form>

<div class="cars-list">
    <div class="loader">
        <div class="loader-circle"></div>
    </div>
    
    @foreach($autos as $auto)
        <div class='car ' data-status-sold="{{$auto->sale ? 'false' : 'true'}}">
            <div class="car-img">
                <img src="{{ asset($auto->logo)}}" alt="" />
            </div>
            <div class="car-content">
                <div class="car-content-header">
                    <span class="car-content-header-brand">{{ $auto->brand }} {{ $auto->model }}</span>
                    <span class="car-content-header-price">{{$auto->price}} €</span>
                </div>
                <div class="car-content-info">
                    <ul class="ar-content-info-list">
                        <li class="car-content-info-list-item">ID <span
                                class="car-content-info-list-item-text">{{ $auto->id }}</span></li>
                        <li class="car-content-info-list-item">{{ __('!_Release') }} <span
                                class="car-content-info-list-item-text">{{$auto->release}} {{ __('!_year') }}</span></li>
                        <li class="car-content-info-list-item">{{ __('!_Mileage') }} <span
                                class="car-content-info-list-item-text">{{$auto->mileage}} {{ __('!_km') }}</span></li>
                    </ul>
                    <a href="{{url($locale.'/cars/'.$auto->id)}}" class="article-item-content-link">
                        {{ __('!_know more')}}
                        <div class="button">
                            <img src="{{ asset('assets/Path.svg')}}" alt=""/>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endforeach
    
    @include('layouts.partials.panginate')
    
</div>    