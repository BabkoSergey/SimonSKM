<?php

namespace App\Http\Controllers\Admin;

use App;
use Artisan;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Illuminate\Support\Arr;

use App\Services\Settings\Settings;
use App\Services\Settings\DotEnvEditor;

use App\Setting;

class SettingController extends Controller
{
      
    /**
     * Settings service instance.
     *
     * @var App\Services\Settings\Settings;
     */
    private $settings;
    
    /**
     * * Settings service instance.
     * 
     * @var App\Services\Settings\DotEnvEditor
     */
    private $dotEnv;

    /**
     * SettingsController constructor.
     *
     * @param Request $request
     * @param Settings $settings
     * @param DotEnvEditor $dotEnv
     */
    public function __construct(Settings $settings, DotEnvEditor $dotEnv)
    {
        $this->middleware('permission:setting main');
        
        $this->settings = $settings;
        $this->dotEnv = $dotEnv;
    }
    
    /**
     * Get all application settings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings['mail'] = $this->dotEnv->loadLike('mail_');        
        $settings['services'] = $this->settings->allLike('services-', true);
        $settings['general'] = $this->settings->allLike('general-', true);  
        if(isset($settings['general']['general-phones']) && empty($settings['general']['general-phones'])){
            $settings['general']['general-phones'] = [];
        }elseif(isset($settings['general']['general-phones'])){
            foreach(explode('%|%', $settings['general']['general-phones']) as $valRow){
                $newVal[$valRow] = $valRow;
            }
            $settings['general']['general-phones'] = $newVal; 
        }        
        $languages = config('app.locale_enabled', [config('app.locale', 'en')]);        
        $langDef = config('app.locale_def', 'en');        
        
        $settings['autos'] = $this->settings->allLikeGroup('autos-', $languages, true);        
                
        return view('admin.settings.general', compact('settings', 'languages', 'langDef'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $type)
    {
        
        $data = $request->all();        
        unset($data['_method'], $data['_token']);

        $codeType = '';
        switch ($type){
            case 'server':
                $codeType = $type;
                $this->dotEnv->write($data);                
                break;
            case 'client':
                reset($data);                
                $codeType = substr(key($data), 0, strpos(key($data), '-'));
                if($codeType == 'general' && !isset($data['general-phones']))
                    $data['general-phones'] = '';
                $this->settings->save($data);                
                break;
            case 'mixed':

                break;
        }

        Artisan::call('cache:clear');

        $hash = $this->setHash($codeType);
                        
        return redirect(route('general.index').$hash);        
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($code)
    {
        
        $this->settings->removeByCode($code);
        
        Artisan::call('cache:clear');

        $codeType = substr($code, 0, strpos($code, '-'));
        $hash = $this->setHash($codeType);
                        
        return redirect(route('general.index').$hash);
    }
    
    private function setHash($codeType){
        
        switch ($codeType){
            case 'autos':
                $hash = '#autos';
                break;
            case 'server':
                $hash = '#mail';
                break;
            case 'services':
                $hash = '#services';
                break;
            case 'general':
                $hash = '';
                break;
            default :
                $hash = '';
        }
        
        return $hash;
    }
        
}
