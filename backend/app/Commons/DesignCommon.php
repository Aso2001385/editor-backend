<?php

namespace App\Commons;

class DesignCommon{

    public static function design(){
        return Storage::get('previews/templates/presetJs.txt');
    }
    public static function preview(){
        return Storage::get('previews/templates/preset.txt');
    }

}
