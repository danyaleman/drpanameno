<?php

use App\AppSettings;

function AppSetting($key) {
    return AppSettings::pluck($key)[0];    
}

?>