<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Feedback;
use App;

use App\Services\Settings\Settings;

class MailController extends Controller
{
    
    /**
     * Settings service instance.
     *
     * @var App\Services\Settings\Settings;
     */
    private $settings;
        
    /**
     * Create a new controller instance.
     *
     * @return void
     */    
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Show the Main Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function feedback(Request $request)
    {
        $error = [];
        if(!$request->get('conditions'))
            $error['conditions'] = 'Not check conditions!';
        if(!$request->get('email') || !$request->get('message'))
            $error['form'] = 'Not valid form!';
                
        if(!empty($error))
            return response()->json(['error'=>$error], 422);
        
        $objData = new \stdClass();
        
        $objData->email = $request->get('email');
        $objData->name = $request->get('name');
        $objData->phone = $request->get('phone');
        $objData->message = $request->get('message');
        
        $to = $this->settings->get('general-email', config('mail.username'));
        
        Mail::to($to)->send(new Feedback($objData));
                                
        return response()->json(['success'=>'ok']);
    }
   
}
