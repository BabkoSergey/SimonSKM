<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Spatie\Permission\Models\Role;
use App;

use App\Services\FilesStorage;
use App\Services\Settings\Settings;
use App\Services\RentDates;

use App\Trailer;
use App\TrailerDisable;
use App\TrailerContent;

class TrailerController extends Controller
{
    private $dates;
     
    private $localeDef;
    private $locales;
    private $filesStorage;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RentDates $dates, FilesStorage $filesStorage, Settings $settings)
    {
        $this->middleware('permission:show trailers');
        $this->middleware('permission:add trailers', ['only' => ['create','store']]);
        $this->middleware('permission:edit trailers', ['only' => ['edit','update']]);
        $this->middleware('permission:delete trailers', ['only' => ['destroy']]);
        $this->middleware('permission:disable trailers', ['only' => ['disable']]);
        
        $this->dates = $dates;
        
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
        $trailers = Trailer::all()->each(function ($trailer, $key) {
                        $trailer->images = $this->filesStorage->getImages('trailer', $trailer->id );
                    });
        
        $resrved = $this->dates->getReservedDates(null, false);
        
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
                
        return view('admin.trailers.main', compact('trailers', 'resrved', 'locales'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.trailers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:trailers,name',   
            'price' => 'regex:/^\d*(\.\d{1,2})?$/',
        ]);
        
        $trailer = Trailer::create($request->input());
        
        $this->filesStorage->createDirectory('trailer', $trailer->id);
        
        return redirect()->route('trailers.index')
                        ->with('success', __('Trailer') .' '.$trailer->name. __('created successfully') . '!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trailer = Trailer::find($id);
                
        $images = $this->filesStorage->getImages('trailer', $id );

        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        return view('admin.trailers.edit', compact( 'trailer', 'images', 'locales' ));
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
        $trailer = Trailer::find($id);
        
        $validate = [
            'name' => ['required'],
            'price' => 'regex:/^\d*(\.\d{1,2})?$/',
            ];
        
        if($request->get('name') != $trailer->name)
            $validate['name'][] = 'unique:trailers,name';
        
        $this->validate($request, $validate);   
                
        $trailer->update($request->input());
        
        foreach($this->locales as $locale){            
            $trailerParams = $request->input($locale); 
            
            if(!$trailerParams || !is_array($trailerParams)){
                $trailerParamsIndex = ['locale' => $locale, 'trailers_id' => $id];
                $trailerParamsValues = ['locale' => $locale, 'trailers_id' => $id, 'spec' => null];
            }else{
                $trailerParamsIndex = ['locale' => $locale, 'trailers_id' => $id];
                $trailerParamsValues = ['locale' => $locale, 'trailers_id' => $id, 'spec' =>  serialize($trailerParams['spec'])];
            }
            
            TrailerContent::updateOrCreate($trailerParamsIndex, $trailerParamsValues);
        }  
        
        return redirect()->route('trailers.index')
                        ->with('success', __('Trailer') .' "'.$trailer->name .'" '. __('updated successfully') . '!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trailer = Trailer::find($id);
        $success = '';
        
        if($trailer){
            $success .= __('Trailer').' ' .$trailer->name.' ';
            $trailer->delete();
        }
        
        $success .= $success != '' ? __('deleted successfully').'!' : '';
        
        return redirect()->route('trailers.index')
                        ->with('success', $success);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * * @param  int  $trailer_id
     * @return \Illuminate\Http\Response
     */
    public function disablesCreate($trailer_id)
    {
        $trailer = Trailer::find($trailer_id);
        $resrved = $this->dates->getReservedDates($trailer_id, false);
        
        return view('admin.trailers.disables_create', compact('trailer', 'resrved'));
    }
        
    /**
     * Store the specified resource from storage.
     *
     * @param  int  $trailer_id
     * @return \Illuminate\Http\Response
     */
    public function disablesStore(Request $request, $trailer_id)
    {
        $validate = [            
            'trailer_id' => 'numeric|exists:trailers,id',                        
            'from' => 'required|date_format:"Y-m-d H:i:s"|before:to',
            'to' => 'required|date_format:"Y-m-d H:i:s"',
        ];
         
        $this->validate($request, $validate);  
        
        TrailerDisable::create($request->input());
        
        $trailer = Trailer::find($trailer_id);
        
        return redirect()->route('trailers.index')
                        ->with('success', __('Trailer') .' '.$trailer->name.' '. __('updated successfully') . '!');
        
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disablesEdit($trailer_id, $disable_id)
    {
        $trailer = Trailer::find($trailer_id);
        $disable = TrailerDisable::find($disable_id);
                
        $resrved = $this->dates->getReservedDates(null, false);
        return view('admin.trailers.disables_edit', compact('trailer', 'disable', 'resrved'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $trailer_id
     * * @param  int  $disable_id
     * @return \Illuminate\Http\Response
     */
    public function disablesUpdate(Request $request, $trailer_id, $disable_id)
    {
        $validate = [            
            'trailer_id' => 'numeric|exists:trailers,id',                        
            'from' => 'required|date_format:"Y-m-d H:i:s"|before:to',
            'to' => 'required|date_format:"Y-m-d H:i:s"',
        ];
       
        $this->validate($request, $validate);  
        
        $disable = TrailerDisable::find($disable_id);
        $disable->update($request->input());
        
        $trailer = Trailer::find($trailer_id);
        
        return redirect()->route('trailers.index')
                        ->with('success', __('Trailer') .' '.$trailer->name.' '. __('updated successfully') . '!');
        
    }
    
    /**
     * Delete the specified resource from storage.
     *
     * @param  str  $ids
     * @return \Illuminate\Http\Response
     */
    public function disablesDelete($trailer_id, $disable_id)
    {
                
        $trailerDisable = TrailerDisable::find($disable_id);
        
        $trailerDisable->delete();
        
        $trailer = Trailer::find($trailer_id);
        
        return redirect()->route('trailers.index')
                        ->with('success', __('Trailer') .' '.$trailer->name.' '. __('updated successfully') . '!');
        
    }
    
}
