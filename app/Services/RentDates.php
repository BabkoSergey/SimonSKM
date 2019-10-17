<?php namespace App\Services;

use DB;
use App\Order;
use App\TrailerDisable;

class RentDates {

    /**
     * Create a new settings service instance.
     */
    public function __construct()
    {
    
    }

    /**
     * Get rent and disables dates.
     *
     * @param int $trailerID
     * @return array
     */
    public function getReservedDates($trailerID=null, $info=true){
               
        $resrved = [];
        $resrvedAll = $this->getReservedDatesCollection($trailerID, false, 'Y-m-d H:i:s')->groupBy('trailer_id')->toArray(); 
        foreach($resrvedAll as $key=>$resrvedTrailer){
            $resrved[$key] = $this->composeEvents($resrvedTrailer, $info);
        }
        
        return $resrved;
    }
    
    /**
     * Get rent and disables dates with Info.
     *
     * @param int $trailerID
     * @return array
     */
    public function getReservedDatesWithInfo($trailerID=null){

        $resrved = $this->getReservedDatesCollection($trailerID, true, 'Y-m-d')
                    ->each(function ($date, $key) {
                        if($date->url){
                            $date->id = $key;
                            $date->classNames = 'calendar-event calendar-event-type-'.$date->type;
                            $date->title = '#'.$date->url.' '. $date->title;
                            $date->url = route('orders.show',['id'=>$date->url]); 
                            $date->allDay = true;
                        }else{
                            $date->id = $key;
                            $date->title = 'Trialer disable dates!';
                            $date->rendering =  'background';
                            $date->color = '#f44e42';
                            $date->allDay = true;
                        }                        
                    })
                    ->groupBy('trailer_id')->first()->toArray();        
                
        return $this->composeEvents($resrved, true);
    }
    
    /**
     * Get rent and disables dates with Info for Calendar.
     *
     * @param int $trailerID
     * @return array
     */
    public function getReservedDatesCalendar($trailerId, $withInfo = true, $setFormat = 'Y-m-d', $all = false){

        $resrvedAll = $this->getReservedDatesCollection($trailerId, $withInfo, $setFormat);
        
        if($withInfo){
            $resrvedAll = $resrvedAll->each(function ($date, $key) {
                        $date->id = $key;
                        $date->title = 'Trialer disable dates!';
                        $date->rendering =  'background';
                        $date->allDay = true;
                        $date->classNames =  'disabled';
                        $date->color = '#f44e42';
                    });
        }

        if(!$all){
            $resrved = $this->composeEvents($resrvedAll->groupBy('trailer_id')->first()->toArray(), $withInfo);
        }else{
            $resrvedAll = $resrvedAll->groupBy('trailer_id');
            foreach($resrvedAll as $key=>$resrvedTrailer){
                $resrved[$key] = $this->composeEvents($resrvedTrailer->toArray(), $withInfo);
            }
        }
                
        return $resrved;
    }
    
    public function getReservedDatesCollection($trailerID, $info = false, $setFormat = 'Y-m-d'){
        
        $whereOrders = ['order_type' => 'payment'];
        $whereTrailers = [];
        
        if($trailerID){
            $whereOrders['trailer_id'] = $trailerID;
            $whereTrailers['trailer_id'] = $trailerID;
        }
        
        $selectOrders = ['trailer_id', 'order_status as type'];
        $selectTrailers = ['trailer_id', DB::raw('"disabled" as type')];
        
        if(!$info){
            $selectOrders = array_merge($selectOrders, ['from' ,'to']);
            $selectTrailers = array_merge($selectTrailers, ['from', 'to']);            
        }else{
            $selectOrders = array_merge($selectOrders, ['from as start', 'to as end', 'id as url', 'payment_status as title']);
            $selectTrailers = array_merge($selectTrailers, ['from as start', 'to as end', DB::raw('"" as url'), DB::raw('"" as title')]);
        }
        
        $orders = Order::select($selectOrders)->where($whereOrders)->whereNotIn('order_status', ['rejected']);
        $trailers = TrailerDisable::select($selectTrailers)->where($whereTrailers);
        
        $dates = function($setDates, $format=null) { 
            $dates = [];            
            foreach(explode(';', $setDates) as $setDate) {
                $dates[] = date($format, strtotime($setDate));
            }
            return $dates;
            
        };
        
        return $orders->union($trailers)->get()
                    ->each(function ($date, $key) use ($dates, $setFormat, $info) { 
                        if(!$info){
                            $date->from = $dates($date->from, $setFormat, $info);
                            $date->to = $dates($date->to, $setFormat, $info);
                        }else{
                            $date->start = $dates($date->start, $setFormat, $info);
                            $date->end = $dates($date->end, $setFormat, $info);
                        }
                    });
                
    }
    
    /**
     * Convert single dates to range.
     *
     * @param array $setDates
     * @param int $trailerId
     * 
     * @return array
     */
    public function convertDatesToRange($setDates, $trailerId = null){

        $resrvedDates = $this->getReservedDatesCalendar($trailerId, false, null);
        
        $rangeSetDates = ['from' => [], 'to' => [], 'price' => [] ];
        
        foreach($setDates as $key=>$date){            
            $setDate = strtotime($date);
            foreach($resrvedDates as $resrved){
                if($setDate >= $resrved['from'] && $setDate <= $resrved['to'])
                    return false;
            }
            
            if($date === reset($setDates)){
                $daysInRange = 0;
                $rangeSetDates['from'][] = $date.' 00:00:00';
                if ($date === end($setDates)){
                    $rangeSetDates['to'][] = $date.' 23:59:59';
                    $daysInRange++;                
                    $rangeSetDates['days'][] = $daysInRange;
                }
            }else{
                $daysInRange++;                
                if( $date !== end($setDates) ){                    
                    if(date('Y-m-d', strtotime($date .' -1 day')) != $setDates[$key-1] ){
                        $rangeSetDates['to'][] = $setDates[$key-1].' 23:59:59';
                        $rangeSetDates['from'][] = $date.' 00:00:00';
                        $rangeSetDates['days'][] = $daysInRange;                    
                        $daysInRange = 0;
                    }
                }else{
                    if(date('Y-m-d', strtotime($date .' -1 day')) != $setDates[$key-1] ){                                        
                        $rangeSetDates['to'][] = $setDates[$key-1].' 23:59:59';                        
                        $rangeSetDates['days'][] = $daysInRange;                                            
                        
                        $rangeSetDates['from'][] = $date.' 00:00:00';
                        $rangeSetDates['to'][] = $date.' 23:59:59';
                        $rangeSetDates['days'][] = 1;                                            
                    }else{
                        $daysInRange++; 
                        $rangeSetDates['to'][] = $date.' 23:59:59';                        
                        $rangeSetDates['days'][] = $daysInRange;                                            
                    }                    
                }
            }
 
        }       
                
        return $rangeSetDates;
    }
    
    private function composeEvents($resrved, $withInfo){
        
        $resrvedEvents =[];
        $from = $withInfo ? 'start' : 'from';
        $to = $withInfo ? 'end' : 'to';
        foreach($resrved as $eventKey=>$event){
            foreach($event[$from] as $eventRangeKey=>$val ){
                $newEevent = $event;
                $newEevent[$from] = $resrved[$eventKey][$from][$eventRangeKey];
                $newEevent[$to] = $resrved[$eventKey][$to][$eventRangeKey];
                if($withInfo){
                    $newEevent['id'] = $resrved[$eventKey]['id'].'_'.$eventRangeKey;
                }
                $resrvedEvents[] = $newEevent;
            }
        }
        
        return $resrvedEvents;
    }
    
}