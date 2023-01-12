<?php

namespace App\Commons;

class CodeTemplate{

    public static function htmlSet($text,$title,$design_name){

        $html = <<<__HTML__
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="./css/sanitize.css" />
                <link rel="stylesheet" href="./css/variable.css" />
                <link rel="icon" href="./assets/images/favicon.png" type="image/png" sizes="24x24">
                <title>$title</title>
                <script type="module">
                    import { styleSetter } from "./js/design-setting.js";
                    import design from "./assets/designs/$design_name.json" assert { type: "json" };
                    styleSetter(design);
                </script>
            </head>
            <body>
                <div id="contents">
                    $text
                </div>
            </body>
            </html>
        __HTML__;

        return $html;
    }
}
?>
