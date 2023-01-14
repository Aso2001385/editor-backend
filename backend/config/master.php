<?php
    $ENV_FIRST = 'MASTER_USER_';
    return [
        'name' => env("{$ENV_FIRST}NAME",null),
        'email' => env("{$ENV_FIRST}MAIL",null),
        'password' => env("{$ENV_FIRST}PASSWORD",null),
    ]

?>
