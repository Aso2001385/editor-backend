<?php

namespace App\Commons;

use Illuminate\Support\Facades\Storage;

class DesignCommon{

    public static function design(){
        return Storage::get('previews/templates/preset.json');
    }
    public static function preview(){
        return Storage::get('previews/templates/preset.txt');
    }

}
