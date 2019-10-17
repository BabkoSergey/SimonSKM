<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController as Controller;
use Illuminate\Http\Request;
use App\Services\RentDates;

use App\Auto;
use App\Order;

class DashboardController extends Controller
{
    private $dates;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RentDates $dates)
    {
        $this->middleware('permission:admin');
        
        $this->dates = $dates;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $infoBoxes['cars'] = Auto::where(['sale'=>true])->count();
        
        $resrved = $this->dates->getReservedDatesWithInfo();
        
        $discounts = function($discount) { 
            $discountTotal = 0;
            foreach (explode(';', $discount) as $subTotal){
                $discountTotal += floatval($subTotal);
            }
            return $discountTotal;            
        };
        
        $prices = function($price, $discount) { 
            $priceTotal = 0;            
            foreach (explode(';', $price) as $subTotal){
                $priceTotal += floatval($subTotal);
            }
            
            return ($priceTotal - $discount);
            
        };
        
        $orders = Order::where(['order_type' => 'payment', 'order_status' => 'new'])->get()->sortByDesc('id')
                ->each(function ($order) use ($discounts, $prices) { 
                    $order->from = explode(';', $order->from);
                    $order->to = explode(';', $order->to);
                    
                    $order->discounts = $discounts($order->discounts);
                    $order->price = $prices($order->price, $order->discounts);
                        
                    });
                
        return view('admin.dashboard',compact('infoBoxes', 'resrved', 'orders'));
    }
}
