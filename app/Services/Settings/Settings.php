<?php namespace App\Services\Settings;

use Illuminate\Support\Facades\Cache;
use App\Setting;
use Illuminate\Support\Collection;

class Settings {

    /**
     * Collection of all application settings.
     *
     * @var Collection
     */
    private $all;

    /**
     * Create a new settings service instance.
     */
    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Get all application settings.
     *
     * @param bool $private
     * @return array
     */
    public function all($private = false)
    {
        $all = $this->all;

        //filter out private (server-only) settings
        if ( ! $private) {
            $all = $all->filter(function(Setting $setting) use($private) {
                return $setting->private === 0;
            });
        }

        return $all->pluck('value', 'name')->toArray();
    }
    
    /**
     * Get all application settings like setting.key.
     *
     * @param string array separator '|' max length 2 $like
     * @param bool $private
     * @return array
     */
    public function allLike($like, $private = false)
    {
        $all = $this->all($private);
        $likeBefore = explode('|', $like)[0];
        $likeAfter = explode('|', $like)[1] ?? null;
                
        $filtered = array_filter($all, function($k) use($likeBefore, $likeAfter){   
            return $likeAfter ? is_int(strpos($k, $likeBefore)) && (is_int(strpos($k, $likeAfter)) && substr($k,-(strlen($likeAfter))) == $likeAfter) : is_int(strpos($k, $likeBefore));                            
        }, ARRAY_FILTER_USE_KEY);

        return $filtered;        
    }
        
    /**
     * Get all application settings like setting.key.
     *
     * @param string array separator '|' max length 2 $like
     * @param bool $private
     * @return array
     */
    public function allLikeGroup($like, $locales=[], $private = false)
    {
        $filtered = [];
        foreach($locales as $locale){
            $result = $this->allLike($like.'|-'.$locale, $private);
            foreach($result as $key=>$val){
                $filtered[$locale][str_replace($like, '', (substr($key,-(strlen('-'.$locale))) == '-'.$locale ? substr($key,0,-(strlen('-'.$locale))) : $key) ) ] = $val;
            }            
        }
        
        return $filtered;        
    }
    
    /**
     * Get all application settings like setting.key.
     *
     * @param string array separator '|' max length 2 $like
     * @param bool $private
     * @return array
     */
    public function allLikeSimpleKey($like, $locale, $private = false)
    {
        $filtered = [];
        
        $result = $this->allLike($like, $private);
        foreach($result as $key=>$val){
            if(substr($key,-(strlen('-'.$locale)), 1) == '-' && substr($key,-(strlen('-'.$locale))) != '-'.$locale )
                continue;
            $filtered[str_replace($like, '', (substr($key,-(strlen('-'.$locale))) == '-'.$locale ? substr($key,0,-(strlen('-'.$locale))) : $key) ) ] = $val;
        }            
        
        return $filtered;        
    }

    /**
     * Get a setting by key or return default.
     *
     * @param string $key
     * @param string|null $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $default;

        if ($setting = $this->find($key)) {
            $value = $setting->value;
        }

        return is_string($value) ? trim($value) : $value;
    }

    /**
     * Get a json setting by key and decode it.
     *
     * @param string $key
     * @param array|null $default
     * @return array
     */
    public function getJson($key, $default = null) {
        $value = $this->get($key, $default);
        if ( ! is_string($value)) return $value;
        return json_decode($value);
    }

    /**
     * Get random setting value from fields that
     * have multiple values separated by newline.
     *
     * @param string $key
     * @param string|null $default
     * @return mixed
     */
    public function getRandom($key, $default = null) {
        $key = $this->get($key, $default);
        $parts = explode("\n", $key);
        return $parts[array_rand($parts)];
    }

    /**
     * Check is setting with specified key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->find($key));
    }

    /**
     * Set single setting. Does not persist in database.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        if ($setting = $this->find($key)) {
            $setting->value = is_array($value) ? implode('%|%', $value) : $value;
        } else {
            $this->all->push(
                new Setting(['name' => $key, 'value' => $value])
            );
        }
    }

    /**
     * Persist specified settings in database.
     *
     * @param array $data
     */
    public function save($data)
    {
        
        foreach ($data as $key => $value) {
            $setting = Setting::firstOrNew(['name' => $key]);
            $setting->value = ! is_null($value) ? (is_array($value) ? implode('%|%', $value) : $value) : '';
            $setting->save();
            $this->set($key, $setting->value);
        }

        Cache::forget('settings.public');
    }
    
    /**
     * Remove specified settings by code.
     *
     * @param array $data
     */
    public function removeByCode($code)
    {           
        $all = $this->all->filter(function(Setting $setting) use($code) {
                return is_int(strpos($setting->name, $code.'-'));
            });

        $all->each->delete();
        
        Cache::forget('settings.public');
    }

    /**
     * Decode settings string from base64 and json.
     *
     * @param $string
     * @return array
     */
    public function decodeSettingsString($string)
    {
        return json_decode(urldecode(base64_decode($string)), true);
    }

    /**
     * Find setting matching specified name.
     *
     * @param string $key
     * @return Setting|null
     */
    private function find($key)
    {
        return $this->all->first(function(Setting $setting) use($key) {
            return $setting->name === $key;
        });
    }

    /**
     * Load settings from database.
     */
    private function loadSettings()
    {       
        $this->all = Cache::remember('settings.public', 1440, function() {
            return Setting::all();
        });        
    }
}