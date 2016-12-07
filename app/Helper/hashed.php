<?php

namespace App\Helper\Hashed;

if (! function_exists('hash_password')) {
    function hash_password($password){
        return \PluginCommonKurir\Helper\Hashed\hash_password($password, config('app.password_salt'));
    }
}