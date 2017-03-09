<?php 

function haet_cleverreach_get_settings(){
    $settings = get_option( 'haet_cleverreach_settings' );
    $default_settings = haet_cleverreach_get_default_settings();
    
    foreach ($default_settings as $key => $value){
        if( !isset($settings[$key]) )
            $settings[$key] = $value;
    }

    return $settings;
}

function haet_cleverreach_save_settings($new_settings){
    $settings = get_option( 'haet_cleverreach_settings' );
    if( empty($settings) ){
        $settings = haet_cleverreach_get_default_settings();
    }

    foreach ($new_settings as $key => $value)
        $settings[$key] = $value;
            
    update_option('haet_cleverreach_settings', $settings);
}


function haet_cleverreach_get_default_settings(){
    return array(
        'show_in_comments'          =>  1,
        'show_in_comments_caption'  =>  __('Sign up for our newsletter','haet_cleverreach'),
        'show_in_comments_defaultchecked'   =>  0,
        'show_at_registration'          =>  0,
        'show_at_registration_caption'  =>  __('Sign up for our newsletter','haet_cleverreach'),
        'show_at_registration_defaultchecked'   =>  0,
        'label_position'    => 'left',
        'message_error'     =>  __('Oops. Something went wrong. Please try again later.','haet_cleverreach'),
        'message_success'   =>  __('Thank you for your subscription.','haet_cleverreach'),
        'message_entry_exists' =>   __('You\'re already subscribed.','haet_cleverreach'),
        'message_invalid_email' =>   __('Please provide a valid email address.','haet_cleverreach'),
        'message_required_field' =>  __('This is a mandatory field.','haet_cleverreach'),
        'signup_form_id'    =>  '',
        'signup_list_id'    =>  ''
    );
}

function print_cleverreach_form($is_widget = false){
    $haet_cleverreach_form = new haet_cleverreach_form();
    $haet_cleverreach_form->show_form($is_widget);
}