<?php

$settings = Osofflineim\Model\Setting::all();
$settings_array = array();
foreach ($settings as $setting) 
{
    $settings_array[$setting->slug] = $setting->value;
}
return $settings_array;