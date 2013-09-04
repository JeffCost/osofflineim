<?php

Autoloader::namespaces(array(
    'Osofflineim\Model' => dirname(__FILE__).DS.'models'.DS,
    'Osofflineim'       => dirname(__FILE__).DS.'libraries'.DS,
));

/*
|--------------------------------------------------------------------------
| Offline Messages Event Listners
|--------------------------------------------------------------------------
|
| Load offline messages listners for module
|
*/
include(dirname(__FILE__).DS.'events'.EXT);