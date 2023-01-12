<?php

namespace App\Commons;

class CodeTemplate{

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
            $lis .= '<li class="nav-item"><a href="'.$item['title'].'_'.$item['number'].'.html">'.$item['title'].'_'.$item['number'].'</a></li>
            ';
        }
        return $lis;
    }
}
?>
