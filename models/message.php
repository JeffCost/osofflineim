<?php namespace Osofflineim\Model;

use \Eloquent;

class Message extends Eloquent {

    public static $table = 'osofflineim_messages';
    public static $key   = 'id';
    
    public static function cleanup($messages)
    {
        if( !empty($messages))
        {
            foreach ($messages as $message) 
            {
                $message->delete();
            }
        }
    }
}