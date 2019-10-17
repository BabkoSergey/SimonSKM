<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Validator;
use App;

use App\Rules\CheckFriendlyURLRule;
use App\Rules\CheckUniqueURLRule;

use Spatie\Permission\Models\Role;
use App\ArtCategory;
use App\ArtCategoryContent;

class ArtCategoryController extends Controller
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
        $this->middleware('permission:show art_categorys');
        $this->middleware('permission:add art_categorys', ['only' => ['create','store']]);
        $this->middleware('permission:edit art_categorys', ['only' => ['edit','update','ban']]);
        $this->middleware('permission:delete art_categorys', ['only' => ['destroy']]);   
        
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

        return view('admin.art_block.categorys.index');
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
        
        $names = function($categorys) { 
            $names = [];
            foreach($categorys as $category) {
                $names[$category->id] = $category->contents()->where('locale', 'ru')->first()->name ?? $category->contents()->where('locale', $this->localeDef)->first()->name;
            }
            return $names;
            
        };
        
        $categorys = $names(ArtCategory::all());
                
        return view('admin.art_block.categorys.create', compact('locales', 'categorys'));
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
            'parent' => 'nullable|numeric',
            'status' => 'required',
            'status' => new CheckUniqueURLRule($request->only($this->locales))
        ];
        
        $warnings = [];
        
        foreach($this->locales as $locale){  
            $rule = [
                $locale . '.name' => 'required',
                $locale . '.url' => [
                    'required',
                    'unique:arts_categorys_contents,url',
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
                
        $inputCategory = $request->only(['logo' ,'status', 'parent']);
        
        $category = ArtCategory::create($inputCategory);
               
        $categorys = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInput['locale'] = $locale;
            
            $categorys[$locale] = ArtCategoryContent::create($contentInput);

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
        $category->contents()->saveMany($categorys);
                
        return redirect()->route('art_categorys.index')
                        ->with('success', __('Category') .' '. __('created successfully') . '!')
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
        $category = ArtCategory::find($id);
                
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        return view('admin.art_block.categorys.show', compact('category','locales'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = ArtCategory::find($id);
                
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        $names = function($categorys) use($locales) { 
            $names = [];
            foreach($categorys as $category) {
                $names[$category->id] = $category->contents()->where('locale', $locales['locale'])->first()->name ?? $category->contents()->where('locale', $locales['def'])->first()->name;
            }
            return $names;
            
        };
        
        $exeptCategorys = ArtCategory::where('id', $id)->with(['childs'])->get()->toArray();        
        $exeption = $this->getRecurseIDs($exeptCategorys, 'childs'); 
                
        $categorys = $names(ArtCategory::whereNotIn('id',$exeption)->get());
        
        return view('admin.art_block.categorys.edit', compact('category','locales', 'categorys'));
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
        $validateCategorys = [
            'order' => 'nullable|numeric|min:0',
            'status' => 'required',
            'status' => new CheckUniqueURLRule($request->only($this->locales))
        ];
        
        $warnings = [];
        
        $category = ArtCategory::find($id);
        
        foreach($this->locales as $locale){  
            $rule = [
                $locale . '.name' => 'required',
                
            ];            
            $warnings[$locale] = $locale;            
            $categoryLocale = $category->contents()->where('locale', $locale)->first();
            
            if($this->localeDef == $locale){
                if(!$categoryLocale || $categoryLocale->url != $request->input($locale.'.url') ){
                    $rule[$locale . '.url'] = [
                            'required',
                            'unique:services_contents,url',
                            new CheckFriendlyURLRule()
                        ];
                }                    
                $validateCategorys = array_merge($validateCategorys, $rule);
                unset($warnings[$locale]);
            }else{
                foreach($request->input($locale) as $attribute){
                    if(!empty($attribute)){
                        if(!$categoryLocale || $categoryLocale->url != $request->input($locale.'.url') ){
                            $rule[$locale . '.url'] = [
                                    'required',
                                    'unique:services_contents,url',
                                    new CheckFriendlyURLRule()
                                ];
                        }                    
                        $validateCategorys = array_merge($validateCategorys, $rule);
                        unset($warnings[$locale]);
                        break;
                    }
                }
            }            
        }

        $this->validate($request, $validateCategorys);
                
        $inputCategory = $request->only(['logo' ,'status', 'parent']);
        $inputCategory['order'] = $inputCategory['order'] ?? 0;
        
        $category->update($inputCategory);
               
        $contents = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInputIndex['locale'] = $locale;
            $contentInputIndex['category_id'] = $category->id;
            
            $contents[$locale] = ArtCategoryContent::updateOrCreate($contentInputIndex, $contentInput);

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
                
        return redirect()->route('art_categorys.index')
                        ->with('success', __('Category') .' '. __('updated successfully') . '!')
                        ->with('warnings', $warningMessages($warnings) );
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $locale = App::getLocale();           
        $categoryIDs = explode(',', $id);
        
        $validator = Validator::make($categoryIDs,['numeric']);
        $success = '';
                
        foreach($categoryIDs as $categoryID){
            
            if($request->get('is_soft')){                
                $category = ArtCategory::where('id', $categoryID)->with('nearestChild')->first();
                
                if($category){
                    foreach($category->nearestChild as $child){
                        $child->parent = $category->parent;
                        $child->save();
                    }                           
                }
                
            }else{                
                $category = ArtCategory::where('id', $id)->with('childs')->first();  
                
                if($category){
                    $categorysChildsIDs = $this->getChildrensIDs($category->childs);
                    $categorysChilds = ArtCategory::whereIn('id', $categorysChildsIDs)->get();
                    foreach($categorysChilds as $categorysChild){
                        $categorysChild->delete();
                    }                    
                }                
            } 
            
            if($category){
                $names = $category->contents()->where('locale', $locale)->first() ?? $category->contents()->where('locale', $this->localeDef)->first(); 
                $success .= ($success == '' ? __('Category').': ' : '') . ($names ? '"'.$names->name.'" ' : null);
                $category->delete();
            }
            
        }
        
        $success .= $success != '' ? __('deleted successfully').'!' : '';
        
        return redirect()->route('art_categorys.index')
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
                
        foreach($banIDs as $categorysID){
            $category = ArtCategory::find($categorysID);            
            
            if($category){                                
                $category->status = $type ?? $category->status ? false : true;
                $category->save();
                $statuses[$categorysID] = $category->status;
            }
            
        }
        
        return response()->json(['success'=>'ok', 'statuses'=>$statuses]);
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function art_categotysDTAjax() {

        $categorys = ArtCategory::all();
        
        $locale = App::getLocale();        
        $localeDef = $this->localeDef;   
                
        $out = datatables()->of($categorys)
                ->addColumn('name', function($categorys) use($locale, $localeDef) {                
                    $names = $categorys->contents()->where('locale', $locale)->first() ?? $categorys->contents()->where('locale', $localeDef)->first();
                    return $names ? $names->name : null;
                })                
                ->addColumn('parent', function($categorys) use($locale, $localeDef) {    
                    $names = $categorys->parent ? $categorys->nearestParent()->first()->contents()->where('locale', $locale)->first() ?? $categorys->nearestParent()->first()->contents()->where('locale', $localeDef)->first() : null;
                    return $names ? $names->name : null;
                })    
                ->addColumn('articles', function($categorys) {                                    
                    return $categorys->articles->count();
                })                
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }   
    
    private function getRecurseIDs($data, $type, $needConvert=false){
        if(empty($data))
            return [];
            
        $collection = $needConvert ? $data->toArray() : $data;        
        $ids = [];
        if(isset($collection['id'])){
            $ids[] = $collection['id'];

                if(!empty($collection[$type]))
                    $ids = array_merge($ids, $this->getRecurseIDs($collection[$type], $type));                                    
        }else{
           foreach($collection as $collect){
                $ids[] = $collect['id'];

                if(!empty($collect[$type]))
                    $ids = array_merge($ids, $this->getRecurseIDs($collect[$type], $type));                                    
            } 
        }
        return $ids;
    }
    
    private function getChildrensIDs($obj){
        
        $results = $this->getRecurseIDs($obj, 'childs', true);
        
        return array_reverse($results);        
    }
       
}
