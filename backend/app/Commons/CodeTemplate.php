<?php

namespace App\Commons;

class CodeTemplate{

    public $slide = [];

    public static function htmlSet($text,$title,$design_name,$lis){

        $html = <<<__HTML__
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./css/sanitize.css" />
            <link rel="stylesheet" href="./css/variable.css" />
            <link rel="stylesheet" href="./css/parts/menu.css" />
            <link rel="stylesheet" href="./css/parts/slide.css" />
            <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
            <link rel="icon" href="./assets/images/language-html5.png" type="image/png" sizes="24x24">
            <title>$title</title>
            <script type="module">
            import { styleSetter } from "./js/design-setting.js";
            import design from "./assets/designs/$design_name.json" assert { type: "json" };
            styleSetter(design);
            </script>
        </head>
        <body>
            <header class="header">
            <button class="burger-btn">
                <span class="bar bar_top"></span>
                <span class="bar bar_mid"></span>
                <span class="bar bar_bottom"></span>
            </button>
            <div class="nav-wrapper">
                <nav class="header-nav">
                <ul class="nav-list">
                    $lis
                </ul>
                </nav>
            </div>
            </header>
            <div style="height: 90vh; overflow-y: scroll">
                <div id="contents">
                $text
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.1/js/swiper.min.js"></script>
            <script src="/js/parts/slide.js"></script>
            <script src="/js/parts/header.js"></script>
        </body>
        </html>
        __HTML__;

        return $html;
    }

    public static function itemSet($items){
        $lis = '';
        logger()->error($items);
        foreach(collect($items)->sortBy('number') as $item) {
            $lis .= '<li class="nav-item">
                <a href="'.$item['title'].'_'.$item['number'].'.html">'.$item['title'].'_'.$item['number'].'</a>
              </li>
            ';
        }
        return $lis;
    }

    public function slideReplace($markdown){

        $text = $markdown;
        $count = 0;
        while ( preg_match('/@!slide[\s\S]+?@!end/', $text, $match)) {
          $imageRows = preg_split('/\n!/',str_replace(['@!slide','@!end'], '',$match[0]));
          $images = '';

          for ($i = 1; $i < count($imageRows); $i++) {
            $imageProperty = explode('](',$imageRows[$i]);
            if ($i === count($imageRows) - 1) $imageProperty[1] = substr( $imageProperty[1], 0 ,-1);
            $imageProperty[1] = substr( $imageProperty[1],0, -1);
            $images .= '<div class="swiper-slide">
                    <img src="'.$imageProperty[1].'" alt="'.substr( $imageProperty[0],1,strlen($imageProperty[0])-1).'">
                </div>
                ';
          }

          $slide = '<div class="swiper">
              <div class="swiper-header"></div>
              <div class="swiper-wrapper">
                '.$images.'
              </div>
              <div class="swiper-pagination"></div>
              <div class="swiper-button-prev"></div>
              <div class="swiper-button-next"></div>
              <div class="swiper-footer"></div>
            </div>';

          $this->slide[$count] = $slide;
          $text = preg_replace('/@!slide[\s\S]+?@!end/','@!BEFOR_SLIDE'.$count++.'@!END',$text,1);

          logger()->error($text);

        }

        return $text;

    }

    public function slideInjection($html){

        $text = $html;


        for($i=0; $i<count($this->slide); $i++){
            logger()->error('$this->slide['.$i.']');
            logger()->error($this->slide[$i]);
            $text = str_replace('<p>@!BEFOR_SLIDE'.$i.'@!END</p>',$this->slide[$i],$text);
        }

        return $text;

    }

}
?>
