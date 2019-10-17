<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\FilesStorage;

class ImageController extends Controller
{
    /**
     * * Storage service instance.
     * 
     * @var App\Services\FilesStorage
     */
    private $filesStorage;

    /**
     * ImageController constructor.
     *     
     * @param FilesStorage $filesStorage
     */
    public function __construct(FilesStorage $filesStorage)
    {                
        $this->filesStorage = $filesStorage;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getUploadImgs()
    {
        $response = $this->filesStorage->getImages(request()->type ?? '', request()->sub ?? null);     
                
        return response()->json($response);      
    }
    
    /**
     * Upload resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function imageUploadPost()
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $visibility = isset(request()->visibility) && request()->visibility == 'private' ? 'private' : 'public' ;

        $response = $this->filesStorage->saveImage(request()->image, request()->type ?? '', $visibility, request()->sub ?? null);     
        
        return response()->json($response);        

    }
    
    /**
     * Upload multi resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function imageUploadMultiPost()
    {
        request()->validate([
            'gallery.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $visibility = isset(request()->visibility) && request()->visibility == 'private' ? 'private' : 'public' ;

        $response['imgs'] = [];
        
        $files = request()->file('gallery');
        foreach($files as $file){
            $response['imgs'][] = $this->filesStorage->saveImage($file, request()->type ?? '', $visibility, request()->sub ?? null);     
        }
        
        return response()->json($response);        

    }
    
    /**
     * Remove resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function imageDeletePost()
    {
        request()->validate([
            'path' => 'required',
            'type' => 'nullable',
            'sub' => 'nullable|numeric',
        ]);
        
        $response = $this->filesStorage->deleteImage(request()->path, request()->type ?? '', request()->sub ?? null);
        
        return response()->json(['success'=>$response],$response ? 200 : 422);        

    }
                     
}
