<?php

namespace App\Commons;

use Illuminate\Support\Facades\Storage;
use ZipArchive;

class NeoZip{

    public $zip ;
    public $path;
    public $root_path;

    function __construct($save_path,$root_path){
        $this->zip = new ZipArchive();
        $this->zip->open($save_path, \ZipArchive::CREATE);
        $this->path = $save_path;
        $this->root_path = $root_path;
    }

    public function put($current_path, $content)
    {
        Storage::put($this->root_path.'/'.$current_path, $content);
        $file_path = storage_path('app/'.$this->root_path.'/'.$current_path);
        $this->zip->addFile($file_path,$current_path);
    }

    public function copy($origin_path,$current_path)
    {
        Storage::copy($origin_path,$this->root_path.'/'.$current_path);
        $file_path = storage_path('app/'.$this->root_path.'/'.$current_path);
        $this->zip->addFile($file_path,$current_path);
    }

    public function close()
    {
        $this->zip->close();
        Storage::deleteDirectory($this->root_path);
        return $this->path;
    }

}
