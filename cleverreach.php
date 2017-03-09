<?php
/*
Plugin Name: CleverReach Newsletter Integration
Plugin URI: http://etzelstorfer.com/
Description: Easily integrate a CleverReach Sign-Up Form in your website. 
Version: 1.7.1
Author: Hannes Etzelstorfer
Author URI: http://etzelstorfer.com
License: GPLv2 or later
*/

/*  Copyright 2017 Hannes Etzelstorfer (email : hannes@etzelstorfer.com) */

define( 'HAET_CLEVERREACH_PATH', plugin_dir_path(__FILE__) );
define( 'HAET_CLEVERREACH_URL', plugin_dir_url(__FILE__) );
define( 'HAET_CLEVERREACH_API_URL', 'http://api.cleverreach.com/soap/interface_v5.1.php?wsdl' );
define( 'HAET_CLEVERREACH_REST_API_URL', 'https://rest.cleverreach.com/v1/' );

require HAET_CLEVERREACH_PATH . 'includes/functions.php';
require HAET_CLEVERREACH_PATH . 'includes/class-api.php';
require HAET_CLEVERREACH_PATH . 'includes/class-form.php';
require HAET_CLEVERREACH_PATH . 'includes/class-widget.php';
require HAET_CLEVERREACH_PATH . 'includes/class-integrations.php';





if (class_exists("haet_cleverreach_form")) {
	$haet_cleverreach_form = new haet_cleverreach_form();
    $haet_cleverreach_integrations = new haet_cleverreach_integrations();
}

if( is_admin() ) {
    require_once HAET_CLEVERREACH_PATH.'includes/class-admin.php';
    $haet_cleverreach_admin = new haet_cleverreach_admin();
}


function haet_cleverreach_init(){
    if(!isset($haet_cleverreach_admin)) {
        require_once HAET_CLEVERREACH_PATH.'includes/class-admin.php';
        $haet_cleverreach_admin = new haet_cleverreach_admin();
    }
    $haet_cleverreach_admin->init();
}
register_activation_hook( __FILE__, 'haet_cleverreach_init');


function haet_cleverreach_deactivate(){
    
}
register_deactivation_hook( __FILE__, 'haet_cleverreach_deactivate');


function haet_cleverreach_load_textdomain() {
    load_plugin_textdomain('haet_cleverreach', false, dirname( plugin_basename( __FILE__ ) ) . '/translations/' );
} 
add_action('plugins_loaded', 'haet_cleverreach_load_textdomain');	

