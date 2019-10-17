<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Validator;
use App;

use App\Rules\CheckFriendlyURLRule;
use App\Rules\CheckUniqueURLRule;

use Spatie\Permission\Models\Role;
use App\Service;
use App\ServiceContent;

class ServiceController extends Controller
{
    
    private $localeDef;
    private $locales;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:show services');
        $this->middleware('permission:add services', ['only' => ['create','store']]);
        $this->middleware('permission:edit services', ['only' => ['edit','update','ban']]);
        $this->middleware('permission:delete services', ['only' => ['destroy']]);   
        
        $this->localeDef = config('app.locale_def', 'en');
        $this->locales = config('app.locale_enabled', []);
        
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.services.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $locales = [
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        return view('admin.services.create', compact('locales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                
        $validateService = [
            'order' => 'nullable|numeric|min:0',
            'status' => 'required',
            'status' => new CheckUniqueURLRule($request->only($this->locales))
        ];
        
        $warnings = [];
        
        foreach($this->locales as $locale){  
            $rule = [
                $locale . '.name' => 'required',
                $locale . '.url' => [
                    'required',
                    'unique:services_contents,url',
                    new CheckFriendlyURLRule()
                ]
            ];            
            $warnings[$locale] = $locale;
            
            if($this->localeDef == $locale){
                $validateService = array_merge($validateService, $rule);
                unset($warnings[$locale]);
            }else{
                foreach($request->input($locale) as $attribute){
                    if(!empty($attribute)){
                        $validateService = array_merge($validateService, $rule);
                        unset($warnings[$locale]);
                        break;
                    }
                }
            }            
        }

        $this->validate($request, $validateService);
                
        $inputService = $request->only(['logo' ,'status', 'order']);
        $inputService['order'] = $inputService['order'] ?? 0;
        
        $service = Service::create($inputService);
               
        $contents = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInput['locale'] = $locale;
            
            $contents[$locale] = ServiceContent::create($contentInput);

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
        $service->contents()->saveMany($contents);
                
        return redirect()->route('services.index')
                        ->with('success', __('Service') .' '. __('created successfully') . '!')
                        ->with('warnings', $warningMessages($warnings) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::find($id);
                
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        return view('admin.services.show', compact('service','locales'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $service = Service::find($id);
                
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        return view('admin.services.edit', compact('service','locales'));
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
        $validateService = [
            'order' => 'nullable|numeric|min:0',
            'status' => 'required',
            'status' => new CheckUniqueURLRule($request->only($this->locales))
        ];
        
        $warnings = [];
        
        $service = Service::find($id);
        
        foreach($this->locales as $locale){  
            $rule = [
                $locale . '.name' => 'required',
                
            ];            
            $warnings[$locale] = $locale;            
            $serviceLocale = $service->contents()->where('locale', $locale)->first();
            
            if($this->localeDef == $locale){
                if(!$serviceLocale || $serviceLocale->url != $request->input($locale.'.url') ){
                    $rule[$locale . '.url'] = [
                            'required',
                            'unique:services_contents,url',
                            new CheckFriendlyURLRule()
                        ];
                }                    
                $validateService = array_merge($validateService, $rule);
                unset($warnings[$locale]);
            }else{
                foreach($request->input($locale) as $attribute){
                    if(!empty($attribute)){
                        if(!$serviceLocale || $serviceLocale->url != $request->input($locale.'.url') ){
                            $rule[$locale . '.url'] = [
                                    'required',
                                    'unique:services_contents,url',
                                    new CheckFriendlyURLRule()
                                ];
                        }                    
                        $validateService = array_merge($validateService, $rule);
                        unset($warnings[$locale]);
                        break;
                    }
                }
            }            
        }

        $this->validate($request, $validateService);
                
        $inputService = $request->only(['logo' ,'status', 'order']);
        $inputService['order'] = $inputService['order'] ?? 0;
        
        $service->update($inputService);
               
        $contents = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInputIndex['locale'] = $locale;
            $contentInputIndex['services_id'] = $service->id;
            
            $contents[$locale] = ServiceContent::updateOrCreate($contentInputIndex, $contentInput);

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
                
        return redirect()->route('services.index')
                        ->with('success', __('Service') .' '. __('updated successfully') . '!')
                        ->with('warnings', $warningMessages($warnings) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locale = App::getLocale();           
        $serviceIDs = explode(',', $id);
        
        $validator = Validator::make($serviceIDs,['numeric']);
        $success = '';
        
        foreach($serviceIDs as $serviceID){
            $service = Service::find($serviceID);
            
            if($service){
            
                $names = $service->contents()->where('locale', $locale)->first() ?? $service->contents()->where('locale', $this->localeDef)->first(); 

                $success .= ($success == '' ? __('Services').': ' : '') . ($names ? '"'.$names->name.'" ' : null);
            
                $service->delete();
            }
        }
        
        $success .= $success != '' ? __('deleted successfully').'!' : '';
        
        return redirect()->route('services.index')
                        ->with('success', $success)
                        ->withErrors($validator);
    }
    
    /**
     * Ban the specified resource from storage.
     *
     * @param  str  $ids
     * @return \Illuminate\Http\Response
     */
    public function ban(Request $request, $ids)
    {
        $banIDs = explode(',', $ids);
        $statuses = [];
        
        $type = $request->get('action') ? ($request->get('action') == 'hold' ? false : true ) : null;
                
        foreach($banIDs as $serviceID){
            $service = Service::find($serviceID);            
            
            if($service){                                
                $service->status = $type ?? $service->status ? false : true;
                $service->save();
                $statuses[$serviceID] = $service->status;
            }
            
        }
        
        return response()->json(['success'=>'ok', 'statuses'=>$statuses]);
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function servicesDTAjax() {

        $services = Service::all();
        
        $locale = App::getLocale();        
        $localeDef = $this->localeDef;   
                
        $out = datatables()->of($services)
                ->addColumn('name', function($services) use($locale, $localeDef) {                
                    $names = $services->contents()->where('locale', $locale)->first() ?? $services->contents()->where('locale', $localeDef)->first();
                    return $names ? $names->name : null;
                })                
                ->addColumn('content', function($services) use($locale, $localeDef) {
                    $contents = $services->contents()->where('locale', $locale)->first() ?? $services->contents()->where('locale', $localeDef)->first();
                    return $contents ? mb_strimwidth(strip_tags($contents->content), 0, 250, "...") : null;
                })                                
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }    
}
