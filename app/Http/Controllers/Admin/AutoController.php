<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Spatie\Permission\Models\Role;
use App;

use App\Services\FilesStorage;
use App\Services\Settings\Settings;

use App\Auto;
use App\AutoContent;

class AutoController extends Controller
{
    private $localeDef;
    private $locales;
    private $filesStorage;
    
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
    public function __construct(FilesStorage $filesStorage, Settings $settings)
    {
        $this->middleware('permission:show autos');
        $this->middleware('permission:add autos', ['only' => ['create','store']]);
        $this->middleware('permission:edit autos', ['only' => ['edit','update','ban']]);
        $this->middleware('permission:delete autos', ['only' => ['destroy']]);   
        
        $this->localeDef = config('app.locale_def', 'en');
        $this->locales = config('app.locale_enabled', []);
        
        $this->filesStorage = $filesStorage;
        $this->settings = $settings;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.autos.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $autos = Auto::all();
        
        $brands = $autos->pluck('brand', 'brand');
        $models = $autos->pluck('model', 'model');
        
        return view('admin.autos.create', compact('brands', 'models'));
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
            'model' => 'required',
            'brand' => 'required',
            'release' => 'required|numeric|digits:4',
        ];
        
        $this->validate($request, $validate);
        
        $auto = Auto::create($request->input());        
        AutoContent::create(['locale' => $this->localeDef,'autos_id' => $auto->id]);
        
        $this->filesStorage->createDirectory('car', $auto->id );        
                                
        return redirect()->route('cars.edit', ['id' => $auto->id])
                        ->with('success', __('Car') .' '.$auto->brand.' '. $auto->model .' '. __('created successfully') . '!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {        
//        $auto = Auto::find($id);
//
//        $images = $this->filesStorage->getImages('car', $id );
//        
//        $locales = [
//            'locale' => App::getLocale(),
//            'avalible' => $this->locales,
//            'def' => $this->localeDef,
//            ];
//            
//        $settings = $this->settings->allLikeGroup('autos-', $this->locales, true);
//        
//        return view('admin.autos.show', compact('auto', 'images', 'locales', 'settings'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $autos = Auto::all();
        $auto = $autos->find($id);

        $images = $this->filesStorage->getImages('car', $id );
        $brands = $autos->pluck('brand', 'brand');
        $models = $autos->pluck('model', 'model');

        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
            
        $settings = $this->settings->allLikeGroup('autos-', $this->locales, true);
        
        return view('admin.autos.edit', compact('auto', 'images', 'brands', 'models', 'locales', 'settings'));
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
        $validate = [
            'model' => 'required',
            'brand' => 'required',
            'release' => 'required|numeric|digits:4',
        ];
        
        $this->validate($request, $validate);
        
        $auto = Auto::find($id);                
        $auto->update($request->input());
        
        foreach($this->locales as $locale){            
            $autoParams = $request->input($locale); 
            
            $autoParamsIndex = ['locale' => $locale, 'autos_id' => $id];
            $autoParamsValues = ['locale' => $locale, 'autos_id' => $id, 'description' => $autoParams['description'], 'spec' => serialize($autoParams['spec'])];
                        
            AutoContent::updateOrCreate($autoParamsIndex, $autoParamsValues);
        }  
        
        return redirect()->route('cars.edit', ['id' => $auto->id])
                        ->with('success', __('Car') .' '.$auto->brand.' '. $auto->model .' '. __('updated successfully') . '!');
    }
    
    public function autosRemoveLogo(Request $request)
    {                
        $auto = Auto::find($request->get('id'));    
        if($auto){
            $auto->update(['logo' => '']);
        }
                            
        return response()->json(['success'=>'ok']);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->filesStorage->deleteDirectory('car', $id );        
        
        Auto::find($id)->delete();
        
        return redirect()->route('cars.index')
                        ->with('success', __('Car') .' '. __('deleted successfully') . '!');
    }
        
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function autosDTAjax() {

        $autos = Auto::all();
                        
        $out = datatables()->of($autos)                
                ->addColumn('actions', '')
                ->toJson();

        return $out;
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
                
        foreach($banIDs as $autoID){
            $auto = Auto::find($autoID);            
            
            if($auto){                                
                $auto->show = $type ?? $auto->show ? false : true;
                $auto->save();
                $statuses[$autoID] = $auto->show;
            }
            
        }
        
        return response()->json(['success'=>'ok', 'statuses'=>$statuses]);
    }
}
