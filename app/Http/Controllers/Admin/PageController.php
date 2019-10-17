<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Validator;
use App;

use App\Rules\CheckFriendlyURLRule;
use App\Rules\CheckUniqueURLRule;

use App\Page;
use App\PageContent;

class PageController extends Controller
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
        $this->middleware('permission:show pages');
        $this->middleware('permission:add pages', ['only' => ['create','store']]);
        $this->middleware('permission:edit pages', ['only' => ['edit','update','ban']]);
        $this->middleware('permission:delete pages', ['only' => ['destroy']]);   
        
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
        $locale = App::getLocale();        
        $localeDef = $this->localeDef;   
        
        $pages = Page::all()->each(function ($page) use($locale, $localeDef) {
                        $page->content = $page->contents()->where('locale', $locale)->first() ?? $page->contents()->where('locale', $localeDef)->first();
                        $page->content->content = $page->content->content ? mb_strimwidth(strip_tags($page->content->content), 0, 250, "...") : null;
                    });
                        
        return view('admin.pages.index', compact('pages'));
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
        
        return view('admin.pages.create', compact('locales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
                
        $validatePage = [ 'url' => ['required', 'unique:pages,url', new CheckFriendlyURLRule()] ];
        
        $warnings = [];
        
        foreach($this->locales as $locale){  
            $rule = [
                $locale . '.name' => 'required',
            ];            
            $warnings[$locale] = $locale;
            
            if($this->localeDef == $locale){
                $validatePage = array_merge($validatePage, $rule);
                unset($warnings[$locale]);
            }else{
                foreach($request->input($locale) as $attribute){
                    if(!empty($attribute)){
                        $validatePage = array_merge($validatePage, $rule);
                        unset($warnings[$locale]);
                        break;
                    }
                }
            }            
        }

        $this->validate($request, $validatePage);
        
        $page = Page::create($request->only(['logo' ,'url']));
               
        $contents = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInput['locale'] = $locale;
            
            $contents[$locale] = PageContent::create($contentInput);

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
        $page->contents()->saveMany($contents);
                
        return redirect()->route('pages.index')
                        ->with('success', __('Page') .' '. __('created successfully') . '!')
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
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        $page = Page::find($id);
        
        return view('admin.pages.show', compact('page','locales'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page = Page::find($id);
                
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        return view('admin.pages.edit', compact('page','locales'));
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
        $warnings = [];
        
        $page = Page::find($id);
        
        $validatePage = $page->url != $request->input('url') ? [ 'url' => ['required', 'unique:pages,url', new CheckFriendlyURLRule()] ] : [];        
        
        foreach($this->locales as $locale){  
            $rule = [
                $locale . '.name' => 'required',                
            ];            
            
            $warnings[$locale] = $locale;            
            
            if($this->localeDef == $locale){                
                $validatePage = array_merge($validatePage, $rule);
                unset($warnings[$locale]);
            }else{
                foreach($request->input($locale) as $attribute){
                    if(!empty($attribute)){                        
                        $validatePage = array_merge($validatePage, $rule);
                        unset($warnings[$locale]);
                        break;
                    }
                }
            }            
        }

        $this->validate($request, $validatePage);                
        $page->update($request->only(['logo' ,'url']));
               
        $contents = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInputIndex['locale'] = $locale;
            $contentInputIndex['pages_id'] = $page->id;
            
            $contents[$locale] = PageContent::updateOrCreate($contentInputIndex, $contentInput);

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
                
        return redirect()->route('pages.index')
                        ->with('success', __('Page') .' '. __('updated successfully') . '!')
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
        $success = '';
               
        $page = Page::find($id);            
        if($page){
            
                $names = $page->contents()->where('locale', $locale)->first() ?? $page->contents()->where('locale', $this->localeDef)->first(); 

                $success .= ($success == '' ? __('Page').': ' : '') . ($names ? '"'.$names->name.'" ' : null);
            
                $page->delete();
        }
                
        $success .= $success != '' ? __('deleted successfully').'!' : '';
        
        return redirect()->route('pages.index')
                        ->with('success', $success);
    }
    
}
