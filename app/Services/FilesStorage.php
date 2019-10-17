<?php namespace App\Services;

use Storage;
use Illuminate\Http\File;

class FilesStorage {

    /**
     * Create a new settings service instance.
     */
    public function __construct()
    {
    
    }

    public function getImages($type, $subFolder = null)
    {                
        $params = $this->getPathByType($type);
                
        $images = Storage::disk($params->disk)->files($params->path.($subFolder ? '/'.$subFolder : ''));
        $pathes = [];
        
        foreach($images as $key=>$image){                
            $images[$key] = Storage::disk($params->disk)->url($image);
            $pathes[$key] = $image;
        }
        
        $response = ['url'=>url(''), 'images'=>$images];
        
        if($params->disk == 'public'){
            $response['pathes'] = $pathes;
        }
        
        return $response;
    }
    
    /**
     * Upload resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function saveImage($IMG, $type, $visibility = 'public', $subFolder = null)
    {                
        $params = $this->getPathByType($type);
                        
        $imageName = Storage::disk($params->disk)->putFile($params->path.($subFolder ? '/'.$subFolder : ''), $IMG, $visibility);
        
        $response = ['url'=>Storage::disk($params->disk)->url($imageName)];
        
        if($params->disk == 'public'){
            $response['pathes'] = $imageName;
        }
        
        return $response;        

    }
    
    /**
     * Remove resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function deleteImage($IMG, $type, $subFolder = null)
    {                
        $params = $this->getPathByType($type);
                                        
        return Storage::disk($params->disk)->delete($IMG);

    }
    
    public function createDirectory($type, $subFolder)
    {
        $params = $this->getPathByType($type);
        
        Storage::disk($params->disk)->makeDirectory($params->path.'/'.$subFolder);
        
        return $params->path.'/'.$subFolder;
    }
    
    public function deleteDirectory($type, $subFolder)
    {
        $params = $this->getPathByType($type);
        
        Storage::disk($params->disk)->deleteDirectory($params->path.'/'.$subFolder);
        
        return $params->path.'/'.$subFolder;
    }    
    
    /**
     * Upload path.
     *
     * @param string $type
     * @return obj $params
     */
    private function getPathByType($type)
    {        
        $params = new \stdClass();        
        switch ($type){
            case 'main':
                $params->path = '/cust';
                $params->disk = 'assets';
                break;            
            case 'service':
                $params->path = '/service';
                $params->disk = 'assets';
                break;
            case 'art_category':
                $params->path = '/art_category';
                $params->disk = 'assets';
                break;
            case 'article':
                $params->path = '/article';
                $params->disk = 'assets';
                break;
            case 'car':
                $params->path = '/uploads/cars';
                $params->disk = 'public';
                break;
            case 'trailer':
                $params->path = '/uploads/trailer';
                $params->disk = 'public';
                break;
            default :
                $params->path = '/uploads';
                $params->disk = 'public';
        }        
        return $params;
    }
    
}