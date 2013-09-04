<?php

Route::post('modules/im/RetrieveMessages', function()
{
    $xml_string = file_get_contents('php://input');

    // supress xml messages if the 
    // string is an invalid xml string
    libxml_use_internal_errors(true);
    
    $user = simplexml_load_string($xml_string);
    if(isset($user->Guid))
    {
        // if 0 get all messages
        $many  = Config::get('osofflineim::settings.osofflineim_delivery_messages');

        $messages = array();

        if($many <= 0)
        {
            $messages = $msg = Osofflineim\Model\Message::where_uuid($user->Guid)
                                        ->order_by('created_at', 'asc')
                                        ->get();

            Osofflineim\Model\Message::cleanup($messages);
        }
        else
        {
            $messages = $msg = Osofflineim\Model\Message::where_uuid($user->Guid)
                                        ->order_by('created_at', 'asc')
                                        ->take($many)->get();
            
            Osofflineim\Model\Message::cleanup($messages);
        }
        
        return View::make('osofflineim::frontend.im.xml_response')->with('messages', $msg);
    }
    else
    {
        Log::error('Error parsing xml string. I got an invalid string.');
        Log::error($xml_string);
        libxml_clear_errors();
        return View::make('osofflineim::frontend.im.xml_response');
    }
});

/**
 *
 * To save the message and delivery successfully later we need to 
 * save the message without the xml headers "<?xml version="1.0" encoding="utf-8"?>"
 *
 * XML Example message
 *
 * <?xml version="1.0" encoding="utf-8"?>
 * <GridInstantMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
 * <fromAgentID>00003636-0000-0000-0000-000000000000</fromAgentID>
 * <fromAgentName>Jeff Cost</fromAgentName>
 * <toAgentID>00004747-0000-0000-0000-000000000000</toAgentID>
 * <dialog>0</dialog>
 * <fromGroup>false</fromGroup>
 * <message>This is a test message...</message>
 * <imSessionID>23000036-0000-0000-0000-000000000000</imSessionID>
 * <offline>1</offline>
 * <Position>
 *     <X>0</X>
 *     <Y>0</Y>
 *     <Z>0</Z>
 * </Position>
 * <binaryBucket />
 * <ParentEstateID>1</ParentEstateID>
 * <RegionID>11000036-0000-0000-0000-000000000000</RegionID>
 * <timestamp>1177668492</timestamp>
 * </GridInstantMessage>
 *
 */
Route::post('modules/im/SaveMessage', function() {

    $xml_string = file_get_contents('php://input');

    libxml_use_internal_errors(true);
    $xml = simplexml_load_string($xml_string);
    //$message = new SimpleXMLElement($xml_string);
    
    if(isset($xml) and !empty($xml))
    {
        $start = strpos($xml_string, "?>");

        if ($start != -1)
        {
            // Remove the xml headers
            /* <?xml version="1.0" encoding="utf-8"?> */
            $msg              = substr($xml_string, $start+2);

            $new_msg          = new Osofflineim\Model\Message;
            $new_msg->id      = Opensim\UUID::random();
            $new_msg->uuid    = $xml->toAgentID;
            $new_msg->message = $msg;
            $new_msg->save();

            $send_email  = Config::get('osofflineim::settings.osofflineim_send_email');
            $db_is_ready = Config::get('settings::core.passes_db_settings');
            if($send_email == '1' and (bool)$db_is_ready)
            {
                // We could use accounts migrated to mwi to have more
                // control over the email settings, but if the users are not in
                // sync with mwi the email won't be sent
                // If we change to search the user in the mwi table
                // we need to change the email template to use avatar_first_name, avatar_last_name
                // instead of firstname and lastname
                
                // $sender_user = Users\Model\User::where_uuid($xml->fromAgentID)->where_status('active')->first();
                $sender_user         = Opensim\Model\Os\UserAccount::where_PrincipalID($xml->fromAgentID)->first();
                $data['sender_user'] = $sender_user;
                
                // $recipient_user = Users\Model\User::where_uuid($xml->toAgentID)->where_status('active')->first();
                $recipient_user = Opensim\Model\Os\UserAccount::where_PrincipalID($xml->toAgentID)->first();
                if(isset($recipient_user) and !empty($recipient_user))
                {

                    $template_id = Config::get('osofflineim::settings.osofflineim_email_template');
                    $template    = Email\Model\Template::find($template_id);
                    
                    $email = array(
                        //'from_email' => $sender_user->email,
                        'to_email'   => $recipient_user->email,
                        'message'    => $template->body,
                        'subject'    => $template->subject,
                    );
                    
                    $date_format     = Config::get('osofflineim::settings.osofflineim_date_format');

                    $data['message'] = $xml->message;
                    $data['user']    = $recipient_user;
                    $data['time']    = date($date_format, (int)$xml->timestamp);

                    $result = Event::fire('send_email', array($email, null, $data));
                }
            }

            return '<?xml version="1.0" encoding="utf-8"?><boolean>true</boolean>';
        }

        return '<?xml version="1.0" encoding="utf-8"?><boolean>false</boolean>';
    }
    else
    {
        return '<?xml version="1.0" encoding="utf-8"?><boolean>false</boolean>';
    }
});