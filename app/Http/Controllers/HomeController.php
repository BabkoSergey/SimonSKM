<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Hash;

use App\Services\Settings\Settings;
use App\Services\FilesStorage;
use App\Services\RentDates;

use App\Service;
use App\ServiceContent;
use App\Article;
use App\ArticleContent;
//use App\ArtsCats;
//use App\ArtCategory;
use App\ArtCategoryContent;
use App\Auto;
//use App\AutoContent;
use App\Trailer;
use App\User;
use App\Order;
use App\OrderSoft;
use App\Page;
use App\PageContent;

class HomeController extends Controller
{
    /**
     * Avalible locales.
     *
     * @var array $locales;
     */            
    private $locales;
    
    /**
     * Default locale.
     *
     * @var string localeDef;
     */    
    private $localeDef;
    
    /**
     * Current locale.
     *
     * @var string $locale;
     */    
    private $locale;
    
    /**
     * Response.
     *
     * @var array $response;
     */ 
    private $response;
    
    /**
     * Request service instance.
     *
     * @var Illuminate\Http\Request;
     */
    private $request;
    
    /**
     * Settings service instance.
     *
     * @var App\Services\Settings\Settings;
     */
    private $settings;
    
    /**
     * FilesStorage service instance.
     *
     * @var App\Services\FilesStorage;
     */
    private $filesStorage;
    
    /**
     * FilesStorage service instance.
     *
     * @var App\Services\RentDates;;
     */
    private $rentDates;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */    
    public function __construct(Request $request, Settings $settings, FilesStorage $filesStorage, RentDates $rentDates )
    {
        $this->localeDef = config('app.locale_def', 'en');
        $this->locales = config('app.locale_enabled', [config('app.locale', 'en')]);
        $this->locale = $this->localeDef;
        
        $this->settings = $settings;
        $this->filesStorage = $filesStorage;
        $this->rentDates = $rentDates;
        
        $this->request = $request;        
        
        $this->response = [];        
    }

    /**
     * Show the Main Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($locale)
    {
        $this->_initResponse($locale);
        
        return view('main')->with($this->response);
    }
    
    /**
     * Show the Static Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function staticPageShow($locale, $slug)
    {
        $this->_initResponse($locale);
                
        $page = Page::where('url',$slug)->first();
        $page->content = $page->contents()->where('locale', $this->locale)->first() ?? $page->contents()->where('locale', $this->localeDef)->first();
        
        if(!$page)
            return $this->_sendError();
        
        $this->_setResponse($page, 'page');
        
        foreach ($this->locales as $useLocale){
            $urls[$useLocale] = $page->url;
        }
        $this->_setUrls($urls);
        
        return view('page')->with($this->response);
    }    
    
    /**
     * Show the Services List Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function servicesIndex($locale)
    {
        $this->_initResponse($locale);
        
        $services = Service::select('id','logo','order')
                ->where('status',true)
                ->orderBy('order', 'ASC')
                ->get();
                
        $useLocale = $this->locale;
        $useLocaleDef = $this->localeDef;
        $services->each(function ($service) use($useLocale, $useLocaleDef) {                        
                        $service->content = $service->contents()->where('locale', $useLocale)->get(['name', 'content', 'url', 'title', 'description'])->first() ?? $service->contents()->where('locale', $useLocaleDef)->get(['name', 'content', 'url', 'title', 'description'])->first();
                    });
                        
        $this->_setResponse($services, 'services');
        
        return view('services-list')->with($this->response);
    }
    
    /**
     * Show the Service Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function serviceShow($locale, $slug)
    {
        $this->_initResponse($locale);
        
        $serviceContent = ServiceContent::where('url',$slug)->first();  
        if(!$serviceContent)
            return $this->_sendError();
        
        $serviceContent->service = $serviceContent->service()->first();
        $this->_setResponse($serviceContent, 'service');
        
        foreach ($this->locales as $useLocale){
            $urls[$useLocale] = $serviceContent->service->contents()->where('locale', $useLocale)->get(['url'])->first()->url ?? $serviceContent->service->contents()->where('locale', $this->localeDef)->get(['url'])->first()->url;
        }
        $this->_setUrls($urls);
        
        return view('services-show')->with($this->response);
    }
    
    /**
     * Show the Services List Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function blogIndex($locale, $slug = null)
    {
        $this->_initResponse($locale);
        
        if($slug){
            $category = ArtCategoryContent::where('url',$slug)->first();
            if(!$category)
                return $this->_sendError();
            
            $articles = $category->category()->first()->articles->where('status',true)->sortByDesc('updated_at');
        }else{            
            $articles = Article::where('status',true)->doesntHave('categorys')->get()->sortByDesc('updated_at');            
        }
        
        $useLocale = $this->locale;
        $useLocaleDef = $this->localeDef;
        $articles->each(function ($article) use($useLocale, $useLocaleDef) {                        
                        $article->content = $article->contents()->where('locale', $useLocale)->get(['name', 'content', 'url', 'title', 'description'])->first() ?? $article->contents()->where('locale', $useLocaleDef)->get(['name', 'content', 'url', 'title', 'description'])->first();
                    });
      
        $this->_setResponse($articles, 'articles');                
                
        return view('articles-list')->with($this->response);
    }
    
    /**
     * Show the Service Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function blogShow($locale, $slug)
    {
        $this->_initResponse($locale);
        
        $articleContent = ArticleContent::where('url',$slug)->first();
        if(!$articleContent)
            return $this->_sendError();
        
        $articleContent->article = $articleContent->article()->first();
        $this->_setResponse($articleContent, 'article');
        
        foreach ($this->locales as $useLocale){
            $urls[$useLocale] = $articleContent->article->contents()->where('locale', $useLocale)->get(['url'])->first()->url ?? $articleContent->article->contents()->where('locale', $this->localeDef)->get(['url'])->first()->url;
        }
        $this->_setUrls($urls);
        
        return view('articles-show')->with($this->response);
    }
    
    /**
     * Show the Cars List Page with paginate & filters.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function autosIndex($locale) {

        $this->_initResponse($locale);
        
        $this->_setAutosList();
         
        return view('autos-list')->with($this->response);
    }
    
    /**
     * Response the Cars List html with paginate & filters.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function autosAjax($locale) {

        $this->_initResponse($locale);
        
        $this->_setAutosList();
         
        return view('autos-list-content')->with($this->response);
    }
        
    /**
     * Show the Car Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function autosShow($locale, $id)
    {
        $this->_initResponse($locale);
        
        $auto = Auto::find($id);
        
        if(!$auto || !$auto->show)
            return $this->_sendError();
        
        $gallery = $this->_getImages('car', $auto);        
        $auto->logo = $gallery['logo'];
        
        $this->_setResponse([
                'auto'      => $auto, 
                'gallery'   => $gallery['images']
            ]);
        
        foreach ($this->locales as $useLocale){
            $urls[$useLocale] = $id;
        }
        
        $autoParams = [
                'description' => $auto->contents()->where('locale', $this->locale)->first()->description ?? $auto->contents()->where('locale', $this->localeDef)->first()->description,
                'params' => []
            ];
        
        $settings = $this->settings->allLikeGroup('autos-', [$this->locale], true);        
        $params = unserialize($auto->contents()->where('locale', $this->locale)->first()->spec);
        $paramsDef = unserialize($auto->contents()->where('locale', $this->localeDef)->first()->spec);
        
        foreach($settings[$this->locale] as $key=>$title){
            $autoParams['params'][$key] = ['title' => $title, 'val' => $params[$key] ?? $paramsDef[$key] ?? null ];
        }
        
        $this->_setResponse($autoParams, 'autoParams');
        
        $autos = Auto::select('id', 'logo', 'model', 'brand', 'release', 'mileage', 'price', 'range', 'sale')
                    ->where('show',true)
                    ->orderBy('sale', 'DESC')->orderBy('range','DESC')->orderBy('id','DESC')
                    ->get()
                    ->slice(0,4)
                    ->each(function ($auto) {
                        $gallery = $this->_getImages('car', $auto);        
                        $auto->logo = $gallery['logo'];
                    });
        
        $this->_setResponse($autos, 'autos');
        
        $this->_setUrls($urls);
        
        return view('autos-show')->with($this->response);
    }
        
    /**
     * Show the Contacts Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contacts($locale)
    {
        $this->_initResponse($locale);
        
        return view('contacts')->with($this->response);
    }
    
    /**
     * Show the Caravan Rent Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function rentIndex($locale, $id = null)
    {
        $this->_initResponse($locale);
        
        $this->_getResrved($id);
        
        $this->_setResponse($this->request->session()->has('cart') ? $this->request->session()->get('cart') : [], 'cart');
        
        $this->_setTrailerParams($id);
        
        $trailer = $id ? Trailer::where('id', $id)->first() : Trailer::first();
        $gallery = $this->_getImages('trailer', $trailer, '/img/trailer-def.png');        
        $trailer->logo = $gallery['logo'];
                
        $trailerContents = ($trailer->contents()->where('locale', $this->locale)->first() ?? $trailer->contents()->where('locale', $this->localeDef)->first())->spec ??
                $trailer->contents()->where('locale', $this->localeDef)->first()->spec ?? null;

        $trailer->spec = $trailerContents ? unserialize($trailerContents) : [];
        
        $this->_setResponse([
                'trailerInfo'      => $trailer,
                'gallery'   => $gallery['images']
            ]);
        
        return view('rent-main')->with($this->response);
    }
    
    /**
     * Show the Cart Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cart($locale)
    {
        $this->_initResponse($locale);
        
        $this->_setResponse($this->request->session()->has('cart') ? $this->request->session()->get('cart') : [], 'cart');
        $this->_getResrved($this->response['cart']['trailer_id'] ?? null);
        
        $this->_setTrailerParams($this->response['cart']['trailer_id'] ?? null);
        
        return view('cart')->with($this->response);
    }    
    
    /**
     * Create Order.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function orderCreate($locale)
    {
        $this->_initResponse($locale);
        
        if(!$this->request->session()->has('cart')){
            return redirect()->back();
        }
        
        $this->validate($this->request, [
                    'conditions' => 'required',
                    'dates' => 'required',
                    'name' => 'required',
                    'email' => 'required|email',
                    'phone' => 'required'
                ]);
        
        $this->_setTrailerParams($this->response['cart']['trailer_id'] ?? null);        
        $rangeSetDates = $this->rentDates->convertDatesToRange(explode(';', $this->request->get('dates')), $this->response['cart']['trailer_id'] ?? null);
        
        if(!$rangeSetDates)
            return redirect()->back();
                      
        $user = User::where('email',$this->request->get('email'))->first();
        
        if(!$user){
            $user = User::create([
                'name' => $this->request->get('name'),
                'email' => $this->request->get('email'),
                'password' => Hash::make('rent_customer'),
                'phone' => $this->request->get('phone'),
                'status' => true
            ]);            
        }else{
            $user->update([
                'name' => $this->request->get('name'),
                'phone' => $this->request->get('phone'),
            ]);            
        }
        
        foreach($rangeSetDates['days'] as $key=>$day){
            $rangeSetDates['days'][$key] = $day*$this->response['trailer']['price'];
        }
        
        $orderParams = [
            'user_id' => $user->id,
            'trailer_id' => $this->response['trailer']['trailerId'],            
            'from' => implode(';', $rangeSetDates['from']),
            'to' => implode(';', $rangeSetDates['to']),
            'price' => implode(';', $rangeSetDates['days']),
            'payment_type' => $this->request->get('checkbox-shoppping-cart') == 'cash' ? 'cash' : 'online',
        ];
          
        $order = Order::create($orderParams);        
        
        OrderSoft::create([    
            'order_id' => $order->id,
            'user_soft' => serialize(User::where('id',$order->user_id)->get(['name', 'email', 'phone', 'first_name', 'last_name'])->first()->toArray()),
            'price_soft' => serialize([]),
            'discounts_soft' => serialize([]),
            'trailer_soft' => serialize(Trailer::where('id',$order->trailer_id)->get(['name', 'description'])->first()->toArray()),
        ]);
        
        $this->request->session()->forget('cart');
        $this->request->session()->flash('order', $order->id);
        
        return redirect($locale.'/confirmation');
    }    
    
    /**
     * Show the Confirmation Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function orderConfirmation($locale)
    {
        if(!$this->request->session()->has('order'))
            return redirect($locale);
        
        $this->_initResponse($locale);
        
        $this->_setResponse(Order::find($this->request->session()->get('order'))->toArray(), 'order');
                
        return view('confirmation')->with($this->response);
        
    }    
    
    /**
     * Cart update.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function cartUpdate($locale)
    {
        if(!$this->request->get('conditions'))
            return redirect()->back();
        
        $this->request->session()->put('cart',
                    [
                        'conditions' => $this->request->get('cart'),
                        'dates' => $this->request->get('dates'),
                        'name' => $this->request->get('name'),
                        'email' => $this->request->get('email'),
                        'phone' => $this->request->get('phone'),
                    ]);
                
        return redirect($locale.'/cart');  
    }    
    
    
        
    /**
     * Show the 404 Page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show404($locale)
    {
        $this->_initResponse($locale);
        
        return $this->_sendError();        
    }
        
    private function _initResponse($locale){
        
        if(!in_array($locale, $this->locales)){        
            $this->_setResponse(['error' => ['Anavalible locale '.$locale.'!']]);
        }else{
            $this->locale = $locale;
        }
        
        $this->_setResponse([
                    'locale'    => $locale,
                    'langs'     => $this->locales,
                    'localeDef' => $this->localeDef
                ]);
        $this->_setMain();
        
        $this->_setUrls();
    }
    
    private function _setUrls($urls = [], $clear = false){
        foreach ($this->locales as $useLocale){            
            $segments = $clear ? [] : request()->segments();
            
            $segments[0] = $useLocale;
            
            if(isset($urls[$useLocale])){
                array_pop($segments);
                $segments[] = $urls[$useLocale];
            }
            
            $localeUrl[$useLocale] = url(implode('/', $segments));
        }
        
        $this->_setResponse(['urls' => $localeUrl ?? [] ]);
    }
    
    private function _setResponse($data, $key=null){
        if(isset($data['error']) && isset($this->response['error'])){
            $data['error'] = array_merge($data['error'], $this->response['error']);
        }elseif($key && $key == 'error'){
            $newData = array_merge($data, $this->response['error']);
            $data = ['error' => $newData];
        }
        
        $this->response = array_merge($this->response, $key? [$key => $data] : $data);
    }
    
    private function _sendError(){
        $this->_setUrls([], true);
        
        return response()->view('errors/404', $this->response, 404);
    }
    
    private function _setMain(){
        
        $general = $this->settings->allLikeSimpleKey('general-', $this->response['locale'], true);
        
        $general['phones'] = isset($general['phones']) && $general['phones'] ? explode('%|%', $general['phones']) : [];
            
        $this->_setResponse(['general' => $general]);            
    }
        
    private function _setPaginate($resource, $name = 'contents', $perPage=null){
        
        $perpage = $perPage ? $perPage : config('app.perpage', 10);
        $pages = ceil($resource->count() / $perpage);
        $page = intval($this->request->get('page')) ? intval($this->request->get('page')) : 1;
        
        $filterParams = '';
        foreach($this->response['filtersSet'] as $filter=>$filterParam){                        
            if($filterParam != [] && (!isset($filterParam['min']) || (isset($filterParam['min']) && $filterParam != $this->response['filters'][$filter]) ) ){
                $filterParams .= ($filterParams != '' ? '&' : '').$filter.'='.implode(';',$filterParam);
            }
        }
        
        $this->_setResponse([                    
                    'pages' => $pages,
                    'page'  => $page,
                    $name => $resource->slice(($page-1)*$perpage, $perpage),
                    'filterParamsStr' => $filterParams != '' ? '&'.$filterParams : ''
                ]);
    }
    
    private function _setAutosList(){
        
        $filteredSet = ['brand' => [], 'model' => [], 'release' => [], 'mileage' => [], 'price' => [] ];
        
        $autos = Auto::select('id', 'logo', 'model', 'brand', 'release', 'mileage', 'price', 'range', 'sale')
                    ->where('show',true)
                    ->orderBy('sale', 'DESC')->orderBy('range','DESC')->orderBy('id','DESC')
                    ->get();
        
        $filters = [   
            'brand'     => $autos->sortBy('brand')->pluck('brand')->unique() ?? [],
            'model'     => $autos->sortBy('model')->pluck('model')->unique() ?? [],
            'release'   => $autos->sortBy('release')->pluck('release')->unique() ?? [],
            'mileage'   => ['min' => $autos->min('mileage') ?? 0, 'max' => $autos->max('mileage') ?? 0,],            
            'price'     => ['min' => $autos->min('price') ?? 0, 'max' => $autos->max('price') ?? 0,],            
        ];    
                
        $filteredSet['brand'] = $this->_unsetUnavalibleFilterSet($filters, 'brand');        
        if(!empty($filteredSet['brand'])){
            $autos = $autos->whereIn('brand', $filteredSet['brand']);            
            
            $filters['model'] = $autos->sortBy('model')->pluck('model')->unique() ?? [];
            $filters['release'] = $autos->sortBy('release')->pluck('release')->unique() ?? [];
            $filters['mileage'] = ['min' => $autos->min('mileage') ?? 0, 'max' => $autos->max('mileage') ?? 0,];
            $filters['price'] = ['min' => $autos->min('price') ?? 0, 'max' => $autos->max('price') ?? 0,];
        }
        
        $filteredSet['model'] = $this->_unsetUnavalibleFilterSet($filters, 'model');        
        if(!empty($filteredSet['model'])){
            $autos = $autos->whereIn('model', $filteredSet['model']);           
            $filters['release'] = $autos->sortBy('release')->pluck('release')->unique() ?? [];
            $filters['mileage'] = ['min' => $autos->min('mileage') ?? 0, 'max' => $autos->max('mileage') ?? 0,];
            $filters['price'] = ['min' => $autos->min('price') ?? 0, 'max' => $autos->max('price') ?? 0,];
        }
        
        $filteredSet['release'] = $this->_unsetUnavalibleFilterSet($filters, 'release');        
        if(!empty($filteredSet['release'])){
            $autos = $autos->whereIn('release', $filteredSet['release']);   
            $filters['mileage'] = ['min' => $autos->min('mileage') ?? 0, 'max' => $autos->max('mileage') ?? 0,];
            $filters['price'] = ['min' => $autos->min('price') ?? 0, 'max' => $autos->max('price') ?? 0,];
        }
        

        $filteredSet['mileage'] = $this->_unsetUnavalibleFilterSet($filters, 'mileage', true);
        $filteredSet['price'] = $this->_unsetUnavalibleFilterSet($filters, 'price', true);
        
        if($filteredSet['mileage'] != $filters['mileage'] || $filteredSet['price'] != $filters['price']){
            $autos = $autos->filter(function ($auto) use ($filteredSet, $filters) {
                                $check = $filteredSet['mileage'] == $filters['mileage'] ? true : ($auto->mileage < $filteredSet['mileage']['min'] || $auto->mileage > $filteredSet['mileage']['max'] ? false : true);
                                $check =  $filteredSet['price'] == $filters['price'] || !$check ? $check : ($auto->price < $filteredSet['price']['min'] || $auto->price > $filteredSet['price']['max'] ? false : $check);
                                return $check;
                            });  
        }
                
        $this->_setResponse(['filters' => $filters, 'filtersSet' => $filteredSet]);
        
        $this->_setPaginate($autos, 'autos');
        
        $withLogo = $this->response['autos']->each(function ($auto) {
                        $gallery = $this->_getImages('car', $auto);        
                        $auto->logo = $gallery['logo'];
                    });
        
        $this->_setResponse(['autos' => $withLogo]);
        
    }
    
    private function _unsetUnavalibleFilterSet($filters, $type, $isMinMax = false){
        
        $result = [];
        
        if($this->request->get($type) && !empty($this->request->get($type)) ){
            $filterSet = explode(';', $this->request->get($type));
            if($isMinMax){ 
                $min = $type == 'price' ? (floatval($filterSet[0]) ? floatval($filterSet[0]) : 0) : (intval($filterSet[0]) ? intval($filterSet[0]) : 0);
                $max = $type == 'price' ? (floatval($filterSet[1]) ? floatval($filterSet[1]) : 0) : (intval($filterSet[1]) ? intval($filterSet[1]) : 0);                
                
                $result = [
                    'min' => $min < $filters[$type]['min'] || $min > $filters[$type]['max'] ? $filters[$type]['min'] : $min ,
                    'max' => $max < $filters[$type]['min'] || $max > $filters[$type]['max'] || $max < $min ? $filters[$type]['max']  : $max
                ];  
            }else{
                $result = array_intersect($filters[$type]->toArray(), $filterSet);
            }
        }        
        
        if($isMinMax && empty($result)){
            $result = $filters[$type];
        }
        
        return $result;
    }

    private function _getImages($type, $resource, $defLogoPath = null){
        
        $gallery = $this->filesStorage->getImages($type, $resource->id );
        
        $images['images'] = $gallery['images'] ?? [];
        $images['logo'] = $resource->logo ? 'storage/'.$resource->logo : ( $gallery['images'][0] ?? asset( $defLogoPath ?? '/assets/car.png') );
        
        return $images;
    }
    
    private function _getResrved($trailerId, $withInfo = true, $setFormat = 'Y-m-d'){
                
        return $this->_setResponse($this->rentDates->getReservedDatesCalendar($trailerId, $withInfo, $setFormat), 'resrved');        
    }
    
    private function _setTrailerParams($trailerId = null){
        
        $trailer = $trailerId ? Trailer::where('id', $trailerId)->first() : Trailer::first();
        
        $this->_setResponse([
                    'price'     => $trailer->price ?? 0,
                    'trailerId'     => $trailer->id,
                ], 'trailer');
        
    }
    
    
}
