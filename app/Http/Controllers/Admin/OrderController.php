<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Validator;
use Carbon\Carbon;

use App\Services\RentDates;

use Spatie\Permission\Models\Role;
use App\Order;
use App\OrderSoft;
use App\User;
use App\Trailer;

class OrderController extends Controller
{

    private $order;
    
    private $dates;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Order $order, RentDates $dates)
    {
        $this->middleware('permission:show orders');
        $this->middleware('permission:add orders', ['only' => ['create','store']]);
        $this->middleware('permission:edit orders', ['only' => ['edit','update']]);
        $this->middleware('permission:delete orders', ['only' => ['destroy']]);  
        
        $this->order = $order;
        $this->dates = $dates;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {                        
        $names = function($users) { 
            $names = [];
            foreach($users as $user) {
                $names[$user->id] = $user->name . ($user->first_name || $user->last_name ? ' (' . $user->first_name . ' ' . $user->last_name .')' : '');
            }
            return $names;
            
        };
        
        $users = $names(User::where('status',true)->get());
        
        $trailers = Trailer::all()->pluck('name', 'id');
        $prices = Trailer::all()->pluck('price', 'id');
        
        $enumsLocale = function($enumKeys) { 
            $enums = [];
            foreach($enumKeys as $k=>$v) $enums[$k] = __($v);
            return $enums;            
        };
        
        $enums['order_type'] = $enumsLocale(getEnumValues($this->order->getTable(), 'order_type'));
        $enums['order_status'] = $enumsLocale(getEnumValues($this->order->getTable(), 'order_status'));
        $enums['payment_type'] = $enumsLocale(getEnumValues($this->order->getTable(), 'payment_type'));
        $enums['payment_status'] = $enumsLocale(getEnumValues($this->order->getTable(), 'payment_status'));
        
        $resrved = $this->dates->getReservedDatesCalendar(null, true, 'Y-m-d', true);
                
        return view('admin.orders.create', compact('users', 'trailers', 'prices', 'enums', 'resrved'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = [
            'user_id' => 'numeric|exists:users,id',
            'trailer_id' => 'numeric|exists:trailers,id',            
            'order_parent' => 'nullable|numeric|exists:orders,id',
            'dates' => 'required',
//            'from' => 'required|date_format:"Y-m-d H:i:s"|before:to',
//            'to' => 'required|date_format:"Y-m-d H:i:s"',
//            'price' => 'regex:/^\d*(\.\d{1,2})?$/',
//            'discounts' => 'regex:/^\d*(\.\d{1,2})?$/',
            'transaction' => 'nullable|date_format:"Y-m-d H:i:s"',
            'order_type' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'order_type'))),
            'order_status' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'order_status'))),
            'payment_type' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'payment_type'))),
            'payment_status' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'payment_status'))),
        ];
         
        $this->validate($request, $validate);   
                
        $rangeSetDates = $this->dates->convertDatesToRange(explode(';', $request->get('dates')), $request->get('trailer_id') ?? null);
        
        if(!$rangeSetDates)
            return redirect()->back()->with('warnings', ['Dates'=>'Dates error!'] );
        
        $trailer = $request->get('trailer_id') ? Trailer::find($request->get('trailer_id')) : Trailer::all()->first();        
        foreach($rangeSetDates['days'] as $key=>$day){
            $rangeSetDates['days'][$key] = $day*$trailer->price;
        }
        
        $order = Order::create([
                    'user_id' => $request->get('user_id'),
                    'trailer_id' => $request->get('trailer_id'),            
                    'from' => implode(';', $rangeSetDates['from']),
                    'to' => implode(';', $rangeSetDates['to']),
                    'price' => implode(';', $rangeSetDates['days']),
                    'payment_type' => $request->get('payment_type'),
                    'transaction' => $request->get('transaction'),
                    'order_type' => $request->get('order_type'),
                    'order_status' => $request->get('order_status'),
                    'payment_status' => $request->get('payment_status'),
                ]); 
                
        OrderSoft::create([    
            'order_id' => $order->id,
            'user_soft' => serialize(User::where('id',$order->user_id)->get(['name', 'email', 'phone', 'first_name', 'last_name'])->first()->toArray()),
            'price_soft' => serialize([]),
            'discounts_soft' => serialize([]),
            'trailer_soft' => serialize(Trailer::where('id',$order->trailer_id)->get(['name', 'description'])->first()->toArray()),
        ]);
                                
        return redirect()->route('orders.index')
                        ->with('success', __('Order') .' '. __('created successfully') . '!');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
        $order = Order::find($id);
        
        $order->from = explode(';', $order->from);
        $order->to = explode(';', $order->to);
        $order->price = explode(';', $order->price);
        $order->discounts = explode(';', $order->discounts);
        $order->priceTotal = 0;
        $order->discountsTotal = 0;        
        foreach ($order->price as $subTotal){
            $order->priceTotal = $order->priceTotal + floatval($subTotal);
        }        
        foreach ($order->discounts as $subTotal){
            $order->discountsTotal = $order->discountsTotal + floatval($subTotal);
        }        
                        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $names = function($users) { 
            $names = [];
            foreach($users as $user) {
                $names[$user->id] = $user->name . ($user->first_name || $user->last_name ? ' (' . $user->first_name . ' ' . $user->last_name .')' : '');
            }
            return $names;
            
        };
        
        $users = $names(User::where('status',true)->get());
        
        $trailers = Trailer::all()->pluck('name', 'id');
        $prices = Trailer::all()->pluck('price', 'id');
        
        $enumsLocale = function($enumKeys) { 
            $enums = [];
            foreach($enumKeys as $k=>$v) $enums[$k] = __($v);
            return $enums;            
        };
        
        $enums['order_type'] = $enumsLocale(getEnumValues($this->order->getTable(), 'order_type'));
        $enums['order_status'] = $enumsLocale(getEnumValues($this->order->getTable(), 'order_status'));
        $enums['payment_type'] = $enumsLocale(getEnumValues($this->order->getTable(), 'payment_type'));
        $enums['payment_status'] = $enumsLocale(getEnumValues($this->order->getTable(), 'payment_status'));

        $resrved = $this->dates->getReservedDatesCalendar(null, true, 'Y-m-d', true);
        $order = Order::find($id);
                
        return view('admin.orders.edit', compact('order', 'users', 'trailers', 'prices', 'enums', 'resrved'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);        
        
        $validate = [
            'user_id' => 'numeric|exists:users,id',
            'trailer_id' => 'numeric|exists:trailers,id',            
            'order_parent' => 'nullable|numeric|exists:orders,id',
            'dates' => 'required',
//            'from' => 'required|date_format:"Y-m-d H:i:s"|before:to',
//            'to' => 'required|date_format:"Y-m-d H:i:s"',
//            'price' => 'regex:/^\d*(\.\d{1,2})?$/',
//            'discounts' => 'regex:/^\d*(\.\d{1,2})?$/',
            'transaction' => 'nullable|date_format:"Y-m-d H:i:s"',
            'order_type' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'order_type'))),
            'order_status' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'order_status'))),
            'payment_type' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'payment_type'))),
            'payment_status' => 'required|in:'.implode(',', array_keys(getEnumValues($this->order->getTable(), 'payment_status'))),
        ];
         
        $this->validate($request, $validate);   
        
        $rangeSetDates = $this->dates->convertDatesToRange(explode(';', $request->get('dates')), $request->get('trailer_id') ?? null);
        
        if(!$rangeSetDates)
            return redirect()->back()->with('warnings', ['Dates'=>'Dates error!'] );
        
        $trailer = $request->get('trailer_id') ? Trailer::find($request->get('trailer_id')) : Trailer::all()->first();        
        foreach($rangeSetDates['days'] as $key=>$day){
            $rangeSetDates['days'][$key] = $day*$trailer->price;
        }
        
        $order->update([
                    'user_id' => $request->get('user_id'),
                    'trailer_id' => $request->get('trailer_id'),            
                    'from' => implode(';', $rangeSetDates['from']),
                    'to' => implode(';', $rangeSetDates['to']),
                    'price' => implode(';', $rangeSetDates['days']),
                    'payment_type' => $request->get('payment_type'),
                    'transaction' => $request->get('transaction'),
                    'order_type' => $request->get('order_type'),
                    'order_status' => $request->get('order_status'),
                    'payment_status' => $request->get('payment_status'),
                ]); 
        
        $orderSoft = OrderSoft::where('order_id',$order->id)->first();
        $orderSoft->update([    
            'user_soft' => serialize(User::where('id',$order->user_id)->get(['name', 'email', 'phone', 'first_name', 'last_name'])->first()->toArray()),
            'price_soft' => serialize([]),
            'discounts_soft' => serialize([]),
            'trailer_soft' => serialize(Trailer::where('id',$order->trailer_id)->get(['name', 'description'])->first()->toArray()),
        ]);
                                        
        return redirect()->route('orders.index')
                        ->with('success', __('Order') .' '. __('created successfully') . '!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orderIDs = explode(',', $id);
        
        $validator = Validator::make($orderIDs,['numeric']);
        $success = '';
        
        foreach($orderIDs as $orderID){
            $order = Order::find($orderID);
            
            if($order){            
                $success .= ($success == '' ? __('Order').': ' : '') . $orderID. ' ';
                $order->delete();
            }
        }
        
        $success .= $success != '' ? __('deleted successfully').'!' : '';
        
        return redirect()->route('orders.index')
                        ->with('success', $success)
                        ->withErrors($validator);
    }
     
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function ordersDTAjax() {

        $orders = Order::all();
                
        $out = datatables()->of($orders)                
                ->addColumn('user', function($orders) {                    
                    return $orders->user ? $orders->user->toArray() : null;
                })                                
                ->editColumn('from', function($orders) {                    
                    $from = explode(';', $orders->from);
                    $to = explode(';', $orders->to);
                    $date = [];
                    foreach($from as $i=>$val){
                        $date[] = Carbon::parse($val)->format('Y-m-d').' to '.Carbon::parse($to[$i])->format('Y-m-d');                        
                    }
                    return $date;
                })  
                ->editColumn('price', function($orders) {                    
                    $price = explode(';', $orders->price);
                    $discounts = explode(';', $orders->discounts);
                    $total = $discountsAll = 0;
                    foreach($price as $i=>$val){
                        $total += floatval($val);
                    }
                    foreach($discounts as $iD=>$valD){
                        $discountsAll += floatval($valD);
                    }
                    
                    return $total - $discountsAll;
                })  
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }  
    
    /**
     * Get the specified resource from storage.
     *
     * @param  str  $ids
     * @return \Illuminate\Http\Response
     */
    public function usersOredersGet($ids)
    {
        $IDs = explode(',', $ids);
        
        foreach($IDs as $userID){
            $response[$userID] = Order::where('user_id',$userID)->whereNotIn('order_type', ['reparation', 'refund'])->get(['id', 'order_type', 'order_status', 'payment_status', 'price', 'discounts', 'transaction'])->sortByDesc('id')->toArray();
        }
        return response()->json(['success'=>'ok', 'orders'=>$response]);
    }
        
}
