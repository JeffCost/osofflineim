<?php

Route::get(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('osofflineim::backend.im@index');
});

// UPDATE SETTINGS
Route::put(ADM_URI.'/(:bundle)', function()
{
    return Controller::call('osofflineim::backend.im@update');
});