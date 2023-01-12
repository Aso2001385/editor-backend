<?php

namespace App\Commons;

use Illuminate\Support\Facades\Http;
use Nette\Schema\Expect;

class Markdown{

    public static function get($text){

        try{
            $response = Http::withToken(config('markdownapi.token'))
            ->withHeaders([
                'Accept'=>config('markdownapi.accept'),
                'Content-type'=>'text/plain'
            ])
            ->post(config('markdownapi.url'),[
                'text'=>$text
            ])->throw()->body();
            return $response;
        }catch(Expect $e){
            return $e;
        }

    }

}
