<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController as Controller;
use Illuminate\Http\Request;
use File;

use App\Rules\CheckTranslateRule;

class TranslateController extends Controller
{
    private $languages;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('permission:admin|setting translate');        
        
        $this->languages = config('app.locale_enabled', []);
    }

    /**
     * Show the translations list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {        
        $columns = [];
	       
	foreach ($this->languages as $key => $language) {
            if ($key == 0) {
                $columns['key'] = $this->openJSONFile($language);
            }
            $columns[$language] = $this->openJSONFile($language);
        }
        return view('admin.settings.translate', compact('columns'));
    }
    
    /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function store(Request $request)
    {
   	
        $request->validate([            
            'key' => [
                        'required',                        
                        new CheckTranslateRule()
                    ], 
	    'value' => 'required',
	]);
		
        $data = $this->openJSONFile('en');
        $data[$request->key] = $request->value;

        $this->saveJSONFile('en', $data);

        return redirect()->route('settings.translate.index');
    }


    /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function destroy($key)
    {        
        foreach ($this->languages as $language) {
            $data = $this->openJSONFile($language);
            unset($data[$key]);
            $this->saveJSONFile($language, $data);
        }
        
        return response()->json(['success' => $key]);
    }


    /**
     * Open Translation File
     * @return Response
    */
    private function openJSONFile($code){
        $jsonString = [];
        
        if(File::exists(base_path('resources/lang/'.$code.'.json'))){
            $jsonString = file_get_contents(base_path('resources/lang/'.$code.'.json'));
            $jsonString = json_decode($jsonString, true);
        }
        
        return $jsonString;
    }


    /**
     * Save JSON File
     * @return Response
    */
    private function saveJSONFile($code, $data){
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/'.$code.'.json'), stripslashes($jsonData));
    }


    /**
     * Save JSON File
     * @return Response
    */
    public function transUpdate(Request $request){
        $data = $this->openJSONFile($request->code);
        $data[$request->pk] = $request->value;

        $this->saveJSONFile($request->code, $data);
        
        return response()->json(['success'=>__('Done')]);
    }


    /**
     * Remove the specified resource from storage.
     * @return Response
    */
    public function transUpdateKey(Request $request){
        
        foreach ($this->languages as $language) {
            $data = $this->openJSONFile($language);
            if (isset($data[$request->pk])) {
                $data[$request->value] = $data[$request->pk];
                unset($data[$request->pk]);
                $this->saveJSONFile($language, $data);
            }
        }

        return response()->json(['success'=>__('Done')]);
    }
}
