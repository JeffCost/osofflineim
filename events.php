<?php

/*
|--------------------------------------------------------------------------
| Offline Messages Listner
|--------------------------------------------------------------------------
|
| Listner to remove old undelivered messages
|
*/
Event::listen('app.cron', function()
{
    $days = Config::get('osofflineim::settings.osofflineim_erase_messages');
    
    // If days is set to 0 we don't want remove any message
    if($days == '0') return;

    $yesterday = date('Y-m-d H:i:s', strtotime(-(int)$days.' day', time()));
    $old_messages = Osofflineim\Model\Message::where('created_at', '<', $yesterday)->get();
    foreach ($old_messages as $message) 
    {
        $message->delete();
    }
    Log::debug('Cron: Deleted old undelivered messages.');
});