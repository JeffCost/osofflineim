<?php

class Osofflineim_Schema_Task {

    public function __construct()
    {
        Bundle::register('osofflineim');
        Bundle::start('osofflineim');

        Bundle::register('modules');
        Bundle::start('modules');

        Bundle::register('email');
        Bundle::start('email');
    }

    public function install()
    {
        $module = Modules\Model\Module::where_slug('osofflineim')->first();
        
        $osofflineim_max_offline_delivery_messages = array(
            'title'       => 'Max Delivery Messages', 
            'slug'        => 'osofflineim_delivery_messages', 
            'description' => 'Delivery only a pre determined number of messages per login. If the user have lots of messages, he/she will have to relog to receive the remaining messages. In some cases sending all messages at once, may crash the viewer.', 
            'type'        => 'select', 
            'default'     => '0',
            'value'       => '0',
            'options'     => '{"0":"Unlimited","10":"10","50":"50","100":"100","500":"500","1000":"1000"}',
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => 1, 
            'module_slug' => 'osofflineim', 
            'module_id'   => $module->id, 
            'order'       => 999, 
        );
        $osofflineim_max_offline_delivery_messages = Osofflineim\Model\Setting::create($osofflineim_max_offline_delivery_messages);


        $osofflineim_erase_old_messages = array(
            'title'       => 'Erase Messages Older Then', 
            'slug'        => 'osofflineim_erase_messages', 
            'description' => 'Users not active for a long time may have lots of old messages saved. Please set the number of days to keep old messages.', 
            'type'        => 'select', 
            'default'     => '0',
            'value'       => '0',
            'options'     => '{"0":"Dont Erase","1":"1 Day","7":"7 Days","15":"15 Days","30":"30 Days","60":"60 Days", "90":"90 Days"}',
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => 1, 
            'module_slug' => 'osofflineim', 
            'module_id'   => $module->id, 
            'order'       => 999, 
        );
        $osofflineim_erase_old_messages = Osofflineim\Model\Setting::create($osofflineim_erase_old_messages);


        $osofflineim_send_email = array(
            'title'       => 'Enable Email', 
            'slug'        => 'osofflineim_send_email', 
            'description' => 'Send an email with the message.', 
            'type'        => 'select', 
            'default'     => '0', 
            'value'       => '0', 
            'options'     => '{"1":"Yes","0":"No"}', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => 1, 
            'module_slug' => 'osofflineim', 
            'module_id'   => $module->id, 
            'order'       => 999, 
        );
        $osofflineim_send_email = Osofflineim\Model\Setting::create($osofflineim_send_email);

        $osofflineim_date_format = array(
            'title'       => 'Email Date Format', 
            'slug'        => 'osofflineim_date_format', 
            'description' => 'How should dates be displayed in the email message Please refer to: <a href="http://php&#46;net/manual/en/function&#46;date&#46;php" target="_blank">date format</a> from PHP&#46;', 
            'type'        => 'text', 
            'default'     => '[G:i]', 
            'value'       => '[G:i]', 
            'options'     => '', 
            'class'       => '', 
            'section'     => '',
            'validation'  => '', 
            'is_gui'      => 1, 
            'module_slug' => 'osofflineim', 
            'module_id'   => $module->id, 
            'order'       => 999, 
        );
        $osofflineim_date_format = Osofflineim\Model\Setting::create($osofflineim_date_format);


        //
        // EMAIL TEMPLATE
        // 
        $activation_email = array(
            'name'        => 'Offline Message Email',
            'slug'        => 'osofflineim_email_template',
            'description' => 'Template that will be sent to the user for offline messages',
            'subject'     => 'Message From {{ sender_user:firstname }} {{ sender_user:lastname }} - {{ settings:site_name }}',
            'body'        => File::get(dirname(__FILE__).DS.'data'.DS.'offline_message.html'),
            'lang'        => 'en',
            'type'        => 'html',
            'module'      => 'osofflineim',
            'is_default'  => 1,
            'is_core'     => 1
        );
        $activation_email = Email\Model\Template::create($activation_email);

        //
        // EMAIL TEMPLATE SETTING FOR OFFLINE MESSAGE MODULE
        // 
        $osofflineim_template_email = array(
            'title'       => 'Offline Message Email',
            'slug'        => 'osofflineim_email_template',
            'description' => 'If offline message email is enabled this is the template that will be sent to the user',
            'type'        => 'select',
            'default'     => $activation_email->id,
            'value'       => $activation_email->id,
            'options'     => 'func:email\get_email_templates',
            'class'       => '',
            'section'     => '',
            'validation'  => '',
            'is_gui'      => '',
            'module_slug' => 'osofflineim',
            'module_id'   => $module->id,
            'order'       => 999, 
        );
        $osofflineim_template_email = Osofflineim\Model\Setting::create($osofflineim_template_email);
    }

    public function uninstall()
    {
        //
        // REMOVE EMAIL TEMPLATES
        // 
        $email_templates = Email\Model\Template::where('module', '=', 'osofflineim')->get();
        
        if(isset($email_templates) and !empty($email_templates))
        {
            foreach ($email_templates as $template) 
            {
                $template->delete();
            }
        }

        //
        // REMOVE SETTINGS
        // 
        $settings = Osofflineim\Model\Setting::where_module_slug('osofflineim')->get();
        
        if(isset($settings) and !empty($settings))
        {
            foreach ($settings as $setting) 
            {
                $setting->delete();
            }
        }
    }

    public function __destruct()
    {
        Bundle::disable('osofflineim');
        Bundle::disable('modules');
        Bundle::disable('email');
    }
}