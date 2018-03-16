<?php
/*
Plugin Name: CleverReach Newsletter Integration
Plugin URI: http://etzelstorfer.com/
Description: Easily integrate a CleverReach Sign-Up Form in your website. 
Version: 2.1
Author: Hannes Etzelstorfer
Author URI: http://etzelstorfer.com
Text Domain: cleverreach
License: GPLv2 or later
*/

/*  Copyright 2017 Hannes Etzelstorfer (email : hannes@etzelstorfer.com) */

define( 'HAET_CLEVERREACH_PATH', plugin_dir_path(__FILE__) );
define( 'HAET_CLEVERREACH_URL', plugin_dir_url(__FILE__) );
define( 'HAET_CLEVERREACH_API_URL', 'http://api.cleverreach.com/soap/interface_v5.1.php?wsdl' );
define( 'HAET_CLEVERREACH_REST_API_URL', 'https://rest.cleverreach.com/v2/' );

require_once HAET_CLEVERREACH_PATH . 'includes/functions.php';
require_once HAET_CLEVERREACH_PATH . 'includes/class-api.php';
require_once HAET_CLEVERREACH_PATH . 'includes/class-api-rest.php';
require_once HAET_CLEVERREACH_PATH . 'includes/class-form.php';
require_once HAET_CLEVERREACH_PATH . 'includes/class-widget.php';
require_once HAET_CLEVERREACH_PATH . 'includes/class-integrations.php';





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



function haet_cleverreach_woocommerce_version_notice() {
    $min_version = '2.0';
    ?>
    <div class="notice notice-warning">
        <p><?php printf( 
                    __( '<strong>Warning:</strong> CleverReach has changed its API, so you really <strong>have to</strong> update CleverReach WooCommerce to version %s.', 'cleverreach' ), $min_version
            ); ?></p>
    </div>
    <?php
}



function haet_cleverreach_2_update_notice() {
    $settings = haet_cleverreach_get_settings();
    if( isset($settings['api_key']) && ( !isset($settings['token']) || $settings['token']=='' ) ):
        ?>
        <div class="notice notice-warning">
            <p><?php printf( 
                        __( '<strong>Warning:</strong> CleverReach has changed its API, please reconnect your Account <a href="%s">here</a> using the button "Connect to CleverReach" and review <strong>all</strong> of your settings.', 'cleverreach' ), admin_url( 'admin.php?page=cleverreach' )
                ); ?></p>
        </div>
        <?php
    endif;
}


function haet_cleverreach_load() {
    load_plugin_textdomain('cleverreach', false, HAET_CLEVERREACH_PATH . '/languages/' );

    if( defined( 'HAET_CLEVERREACHWOOCOMMERCE_PATH' ) ){
        $woocommerce_plugin_data = get_plugin_data( HAET_CLEVERREACHWOOCOMMERCE_PATH.'/cleverreach-woocommerce.php' );
        
        if( version_compare( $woocommerce_plugin_data['Version'] , '2.0', '<') )
            add_action( 'admin_notices', 'haet_cleverreach_woocommerce_version_notice' );
    }

    add_action( 'admin_notices', 'haet_cleverreach_2_update_notice' );
} 
add_action('plugins_loaded', 'haet_cleverreach_load');	

// $settings = haet_cleverreach_get_settings();
// $settings['api_key'] = 'xxx';
// haet_cleverreach_save_settings( $settings );

