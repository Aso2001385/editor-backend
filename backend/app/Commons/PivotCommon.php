<?php

namespace App\Commons;

class PivotCommon{

    public static function pivot_switch($default_arrays,...$pivots){
        foreach($pivots as $pivot) if(is_array($pivot)) return $pivot;
        return $default_arrays;
    }

}
