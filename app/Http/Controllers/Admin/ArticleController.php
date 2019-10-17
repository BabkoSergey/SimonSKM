<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController as Controller;
use Validator;
use App;

use App\Rules\CheckFriendlyURLRule;
use App\Rules\CheckUniqueURLRule;

use Spatie\Permission\Models\Role;
use App\Article;
use App\ArticleContent;
use App\ArtCategory;
use App\ArtsCats;

class ArticleController extends Controller
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
        $this->middleware('permission:show articles');
        $this->middleware('permission:add articles', ['only' => ['create','store']]);
        $this->middleware('permission:edit articles', ['only' => ['edit','update','ban']]);
        $this->middleware('permission:delete articles', ['only' => ['destroy']]);   
        
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
        return view('admin.art_block.articles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        
        $categorys = $names(ArtCategory::all());
                
        return view('admin.art_block.articles.create', compact('locales', 'categorys'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateArticle = [
            'category_id' => 'nullable|numeric|exists:articles_categorys,id',
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
                $validateArticle = array_merge($validateArticle, $rule);
                unset($warnings[$locale]);
            }else{
                foreach($request->input($locale) as $attribute){
                    if(!empty($attribute)){
                        $validateArticle = array_merge($validateArticle, $rule);
                        unset($warnings[$locale]);
                        break;
                    }
                }
            }            
        }

        $this->validate($request, $validateArticle);
                
        $inputArticle = $request->only(['logo' ,'status' ]);
        
        $article = Article::create($inputArticle);
               
        $articles = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInput['locale'] = $locale;
            $contentInput['articles_id'] = $article->id;
            
            $articles[$locale] = ArticleContent::create($contentInput);
            
            if($request->get('category_id')){
                $this->assignCategorys($article->id, $request->input('category_id'), true);
            }

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
        $article->contents()->saveMany($articles);
                
        return redirect()->route('articles.index')
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
        $article = Article::find($id);
                
        $locales = [
            'locale' => App::getLocale(),
            'avalible' => $this->locales,
            'def' => $this->localeDef,
            ];
        
        return view('admin.art_block.articles.show', compact('article','locales'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::find($id);
                
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
        
        $categorys = $names(ArtCategory::all());
                
        return view('admin.art_block.articles.edit', compact('article', 'locales', 'categorys'));
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
            'category_id' => 'nullable|numeric|exists:articles_categorys,id',
            'status' => 'required',
            'status' => new CheckUniqueURLRule($request->only($this->locales))
        ];
        
        $warnings = [];
        
        $article = Article::find($id);
                
        foreach($this->locales as $locale){  
            $rule = [
                $locale . '.name' => 'required',                
            ];            
            $warnings[$locale] = $locale;            
            $articleLocale = $article->contents()->where('locale', $locale)->first();
            
            if($this->localeDef == $locale){
                if(!$articleLocale || $articleLocale->url != $request->input($locale.'.url') ){
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
                        if(!$articleLocale || $articleLocale->url != $request->input($locale.'.url') ){
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
                
        $inputService = $request->only(['logo' ,'status']);        
        
        $article->update($inputService);
        
        if($request->has('category_id')){
            $request->get('category_id') ? $this->assignCategorys($article->id, $request->input('category_id'), true) : $this->removeCategorys($article->id, $article->categorys()->pluck('category_id')->toArray(), true);
        }
               
        $contents = [];        
        foreach(array_diff($this->locales,$warnings) as $locale){            
            $contentInput = $request->input($locale);
            $contentInputIndex['locale'] = $locale;
            $contentInputIndex['articles_id'] = $article->id;
            
            $contents[$locale] = ArticleContent::updateOrCreate($contentInputIndex, $contentInput);

        }        
        
        $warningMessages = function($warnings) { foreach($warnings as $key=>$locale) $warnings[$key] = __($locale.'_'.ucfirst($locale)).'! '.__('Values not set. The default locale will be used.'); return !empty($warnings) ? $warnings : null; };
                
        return redirect()->route('articles.index')
                        ->with('success', __('Article') .' '. __('updated successfully') . '!')
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
        $articleIDs = explode(',', $id);
        
        $validator = Validator::make($articleIDs,['numeric']);
        $success = '';
        
        foreach($articleIDs as $articleID){
            $article = Article::find($articleID);
            
            if($article){
            
                $names = $article->contents()->where('locale', $locale)->first() ?? $article->contents()->where('locale', $this->localeDef)->first(); 

                $success .= ($success == '' ? __('Articles').': ' : '') . ($names ? '"'.$names->name.'" ' : null);
            
                $article->delete();
            }
        }
        
        $success .= $success != '' ? __('deleted successfully').'!' : '';
        
        return redirect()->route('articles.index')
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
                
        foreach($banIDs as $articleID){
            $article = Article::find($articleID);            
            
            if($article){                                
                $article->status = $type ?? $article->status ? false : true;
                $article->save();
                $statuses[$articleID] = $article->status;
            }
            
        }
        
        return response()->json(['success'=>'ok', 'statuses'=>$statuses]);
    }
    
    /**
     * Datatable Ajax fetch
     *
     * @return
     */
    public function articlesDTAjax() {

        $articles = Article::all();
        
        $locale = App::getLocale();        
        $localeDef = $this->localeDef;   
                
        $out = datatables()->of($articles)
                ->addColumn('name', function($articles) use($locale, $localeDef) {                
                    $names = $articles->contents()->where('locale', $locale)->first() ?? $articles->contents()->where('locale', $localeDef)->first();
                    return $names ? $names->name : null;
                })    
                ->addColumn('category', function($articles) use($locale, $localeDef) { 
                    $category = $articles->categorys()->first();
                    $names = $category ? ( $category->contents()->where('locale', $locale)->first() ?? $category->contents()->where('locale', $localeDef)->first()) : null;
                    return $names ? $names->name : null;
                })                    
                ->addColumn('content', function($articles) use($locale, $localeDef) {
                    $contents = $articles->contents()->where('locale', $locale)->first() ?? $articles->contents()->where('locale', $localeDef)->first();
                    return $contents ? mb_strimwidth(strip_tags($contents->content), 0, 250, "...") : null;
                })                                
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    } 
    
    private function assignCategorys($articleID, $categorys, $override=true){
        
        $articleCats = ArtsCats::where('article_id', $articleID)->pluck('category_id')->toArray();
        $categoryIDs = !is_array($categorys) ? [$categorys] : $categorys;
        
        $intersect = array_intersect($categoryIDs, $articleCats);        
        
        $addNew = array_diff($categoryIDs, $intersect);        
        $remOld = array_diff($articleCats, $intersect);
        
        foreach($addNew as $categoryID){
            ArtsCats::create(['article_id'=>$articleID, 'category_id'=>$categoryID]);
        }
        
        if($override)
            ArtsCats::where('article_id', $articleID)->whereIn('category_id', $remOld)->delete();
        
        return $override ? $categoryIDs : array_merge($categoryIDs, $remOld);
    }
    
    private function removeCategorys($articleID, $categoryIDs){
                
        $categoryRemoveIDs = !is_array($categoryIDs) ? [$categoryIDs] : $categoryIDs;
        
        ArtsCats::where('article_id', $articleID)->whereIn('category_id', $categoryRemoveIDs)->delete();
        
        return ArtsCats::where('article_id', $articleID)->pluck('category_id')->toArray();
    }
    
}
