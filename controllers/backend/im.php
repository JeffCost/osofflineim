<?php

class Osofflineim_Backend_Im_Controller extends Admin_Controller {
    
    public function __construct()
    {
        parent::__construct();
        
        $this->data['section_bar'] = array(
            __('osofflineim::lang.Settings')->get(ADM_LANG) => URL::base().'/'.ADM_URI.'/osofflineim',
        );

        $this->data['bar'] = array(
            'title'       => __('osofflineim::lang.OpenSim Messages')->get(ADM_LANG),
            'url'         => URL::base().'/'.ADM_URI.'/osofflineim',
            'description' => __('osofflineim::lang.Handles opensim offline messages.')->get(ADM_LANG),
        );
    }

    public function get_index()
    {
        $this->data['section_bar_active'] = Lang::line('osofflineim::lang.Settings')->get(ADM_LANG);
        $this->data['settings'] = Osofflineim\Model\Setting::order_by('order', 'asc')->get();
        return $this->theme->render('osofflineim::backend.im.index', $this->data);
    }

    public function put_update()
    {
        $post_data = Input::get('order');
        
        if(isset($post_data) and !empty($post_data))
        {
            $order_items = explode(',', $post_data);
            foreach ($order_items as $order_position => $slug)
            {
                $affected = Osofflineim\Model\Setting::where_slug($slug)
                               ->update(array('order' => $order_position));
            }
            return;
        }


        $settings = Input::all();
        
        foreach ($settings as $slug => $value)
        {
            // Update runtime configurations.
            $setting = Config::get('osofflineim::settings.'.$slug);
            if($setting != null)
            {
                Config::set('osofflineim::settings.'.$slug, $value);
            }
            // Update database configurations
            $affected = Osofflineim\Model\Setting::where_slug($slug)
                                                ->update(array('value' => $value));
        }
        
        $this->data['message'] = Lang::line('osofflineim::lang.Settings were successfully updated')->get(ADM_LANG);
        $this->data['message_type'] = 'success';

        return Redirect::back()->with($this->data);
    }
}