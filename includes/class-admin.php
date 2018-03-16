<?php

class haet_cleverreach_admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_page_scripts_and_styles' ) );
		add_action( 'admin_init', array( $this,'register_settings' ) );
	}


	/**
	 *	Plugin Initialization
	 */
	public function init(){
		
	}

	public function register_settings(){
		$options = haet_cleverreach_get_settings();
		register_setting( 'haet_cleverreach_option_group', 'haet_cleverreach_settings', array( $this, 'validate_settings' ) );
		// page general
		add_settings_section( 
				'general_settings', 
				__('General settings','cleverreach' ), 
				array($this,'section_intro'), 
				'toplevel_page_cleverreach'
			);


		// Only show the old SOAP API key for compatibility reasons, not for new installations
		if( isset( $options['api_key'] ) && $options['api_key'] ):
			add_settings_field( 
					'api_key', 
					__( 'CleverReach API key', 'cleverreach' ), 
					array($this,'field_api_key'), 
					'toplevel_page_cleverreach', 
					'general_settings'
				);
		endif;

		add_settings_field( 
				'token', 
				__( 'CleverReach Connection', 'cleverreach' ), 
				array($this,'field_token'), 
				'toplevel_page_cleverreach', 
				'general_settings'
			);



		// page integrations
		// section comments
		add_settings_section( 
				'integration_comments', 
				__('Comment Form Integration','cleverreach' ), 
				array($this,'section_intro'), 
				'cleverreach_page_cleverreach-integrations'
			);
		add_settings_field( 
				'show_in_comments', 
				__( 'Show checkbox in comments', 'cleverreach' ), 
				array($this,'field_show_in_comments'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);
		add_settings_field( 
				'show_in_comments_caption', 
				__( 'Checkbox caption', 'cleverreach' ), 
				array($this,'field_show_in_comments_caption'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);
		add_settings_field( 
				'show_in_comments_form', 
				__( 'CleverReach form', 'cleverreach' ), 
				array($this,'field_show_in_comments_form'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);
		add_settings_field( 
				'show_in_comments_name_attribute', 
				__( 'Name attribute', 'cleverreach' ), 
				array($this,'field_show_in_comments_name_attribute'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);
		add_settings_field( 
				'show_in_comments_defaultchecked', 
				__( 'Checked by default', 'cleverreach' ), 
				array($this,'field_show_in_comments_defaultchecked'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);

		// section registration
		// add_settings_section( 
		// 		'integration_registration', 
		// 		__('Registration Integration','cleverreach' ), 
		// 		array($this,'section_intro'), 
		// 		'cleverreach_page_cleverreach-integrations'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration', 
		// 		__( 'Show checkbox at registration', 'cleverreach' ), 
		// 		array($this,'field_show_at_registration'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration_caption', 
		// 		__( 'Checkbox caption', 'cleverreach' ), 
		// 		array($this,'field_show_at_registration_caption'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration_form', 
		// 		__( 'CleverReach form', 'cleverreach' ), 
		// 		array($this,'field_show_at_registration_form'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration_defaultchecked', 
		// 		__( 'Checked by default', 'cleverreach' ), 
		// 		array($this,'field_show_at_registration_defaultchecked'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		
		// page form
		add_settings_section( 
				'form_settings', 
				__('Form Settings','cleverreach' ), 
				array($this,'section_intro'), 
				'cleverreach_page_cleverreach-forms'
			);
		add_settings_field( 
				'message_success', 
				__( 'Success message', 'cleverreach' ), 
				array($this,'field_message_success'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_invalid_email', 
				__( 'Invalid Email', 'cleverreach' ), 
				array($this,'field_message_invalid_email'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_entry_exists', 
				__( 'Already subscribed', 'cleverreach' ), 
				array($this,'field_message_entry_exists'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_error', 
				__( 'An Error occured', 'cleverreach' ), 
				array($this,'field_message_error'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_required_field', 
				__( 'Required Field Error', 'cleverreach' ), 
				array($this,'field_message_required_field'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'label_position', 
				__( 'Label position', 'cleverreach' ), 
				array($this,'field_label_position'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'attributes_used', 
				'', 
				array($this,'field_attributes_used'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'attributes_available', 
				'', 
				array($this,'field_attributes_available'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);



	}	


	public function validate_settings( $fields ){
		$text_fields = array( 'api_key', 'token', 'redirect', 'css' );

		//merge with existing or default values
		$settings = haet_cleverreach_get_settings();
		if( empty($settings) ){
		    $settings = haet_cleverreach_get_default_settings();
		}

		foreach( $fields as $field_name => $field_value ) {
			if( in_array( $field_name, $text_fields ) ) {
				$fields[ $field_name ] = sanitize_text_field( $field_value );
			}
			$settings[$field_name] = $field_value;
		}

		if( isset($settings['token']) && $settings['token'] != '' )
			$settings['api_key'] = '';

		return $settings;
	}





	/**
	 *	created Admin pages for CleverReach
	 */
	public function add_admin_pages() {
		add_menu_page( 
			'CleverReach', 
			'CleverReach', 
			apply_filters( 'haet_cleverreach_capabilities', 'manage_options' ), 
			'cleverreach', 
			array( $this, 'show_settings_general' ), 
			HAET_CLEVERREACH_URL. 'images/menu-icon.png', 
			'99.3235345' 
		);

		add_submenu_page(
			'cleverreach',
			__('CleverReach Form Integrations','cleverreach'),
			__('Integrations','cleverreach'),
			apply_filters( 'haet_cleverreach_capabilities', 'manage_options' ),
			'cleverreach-integrations',
			array( $this, 'show_settings_integrations' )
		);

		add_submenu_page(
			'cleverreach',
			__('Form Builder','cleverreach'),
			__('Form Builder','cleverreach'),
			apply_filters( 'haet_cleverreach_capabilities', 'manage_options' ),
			'cleverreach-forms',
			array( $this, 'show_settings_form' )
		);
	}


	/**
	 *	Load Admin JS and CSS
	 */
    public function admin_page_scripts_and_styles($page){
    	//echo $page;
    	if(	strpos($page, 'page_cleverreach') ){
	        wp_enqueue_script('haet_cleverreach_admin_script',  HAET_CLEVERREACH_URL.'/js/admin_script.js', array( 'jquery-ui-sortable','jquery'));
	        wp_localize_script( 'haet_cleverreach_admin_script', 'haet_cr_ajax',
	                    array( 
	                    	'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
	                    	'translations'	=> array(
	                    	 	'label'	=>	__( 'Label', 'cleverreach' ),
	                    	 	'text'	=>	__( 'Text', 'cleverreach' ),
	                    	 	'available_options'	=>	__( 'Available Options', 'cleverreach' ),
	                    	 )
	                   	) 
	                );
	        wp_enqueue_style('haet_cleverreach_admin_style',  HAET_CLEVERREACH_URL.'/css/backend.css');
	    }
    }
	 


    /**
     *	Settings Page General
     */
	public function show_settings_general(){
		$settings = haet_cleverreach_get_settings();
		$api_connected = false;
		$api_message = __('Please click the button below to connect to your CleverReach account.<br><br>If you don\'t have an account yet <a href="http://www.cleverreach.com/?rk=74089ojcjrsrb" target="_blank">SIGNUP HERE</a> for a free newsletter system for up to 250 receivers.','cleverreach');
		$refresh_lists = false;
		$list_result = array();

		
		if( isset($settings['token']) && $settings['token'] != '' ){
			// REST
			$api = new haet_cleverreach_api_rest( $settings['token'] );
			$api_test_result = $api->test_token();
			$api_connected = $api_test_result['success'];
			$api_message =  $api_test_result['message'];

			if( true === $api_connected )
				$refresh_lists = true;

			if( $refresh_lists ) {
				$list_result = $api->get_lists();
				if( $list_result['success'] ){
					$settings['lists'] = $list_result['lists'];
					haet_cleverreach_save_settings( $settings );
				}
			}
		}elseif( isset($settings['api_key']) && $settings['api_key'] != '' ){
			// SOAP 
			$api = new haet_cleverreach_api( $settings['api_key'] );
			$api_test_result = $api->test_api_key();
			$api_connected = $api_test_result['success'];
			$api_message =  $api_test_result['message'];

			if( true === $api_connected && ( !isset($settings['lists']) || isset($_POST['haet_cleverreach_refresh']) ) )
				$refresh_lists = true;

			if( $refresh_lists ) {
				$list_result = $api->get_lists();
				if( $list_result['success'] ){
					$settings['lists'] = $list_result['lists'];
					haet_cleverreach_save_settings( $settings );
				}
			}
		}

		require HAET_CLEVERREACH_PATH . 'views/admin/settings-general.php';
	}


	/**
	 *	Settings Page Integrations
	 */
	public function show_settings_integrations(){
		$settings = haet_cleverreach_get_settings();

		require HAET_CLEVERREACH_PATH . 'views/admin/settings-integrations.php';
	}


	/**
	 *	Settings Page Form
	 */
	public function show_settings_form(){
		$settings = haet_cleverreach_get_settings();
		
		if( isset($_POST['haet_cleverreach_get_fields']) ){ 
			//REST
			if( isset($settings['token']) && $settings['token'] != '' ){
				$api = new haet_cleverreach_api_rest( $settings['token'] );
				$list_form = $_POST['haet_cleverreach_get_fields'];
				$list_form_array = explode('-', $list_form);
				$form_id = $list_form_array[1];

				$list_id = $list_form_array[0];
				$settings['signup_form_id'] = $form_id;
				$settings['signup_list_id'] = $list_id;
				haet_cleverreach_save_settings( $settings );
				$attributes_result = $api->get_list_attributes($list_id);

				if( $attributes_result['success'] ){
					$attributes = array_merge( $attributes_result['global_attributes'], $attributes_result['list_attributes'] );
				}
			}elseif( isset($settings['api_key']) && $settings['api_key'] != '' ){
				//SOAP
				$api = new haet_cleverreach_api( $settings['api_key'] );
				$list_form = $_POST['haet_cleverreach_get_fields'];
				$list_form_array = explode('-', $list_form);
				$form_id = $list_form_array[1];

				$list_id = $list_form_array[0];
				$settings['signup_form_id'] = $form_id;
				$settings['signup_list_id'] = $list_id;
				haet_cleverreach_save_settings( $settings );
				$attributes_result = $api->get_list_attributes($list_id);

				if( $attributes_result['success'] ){
					$attributes = $attributes_result['attributes'];
				}
			}
		}
		require HAET_CLEVERREACH_PATH . 'views/admin/settings-form.php';
	}




	public function section_intro(){
		
	}

	private function field_helper_show_options_form($lists,$selected_value){
		?>
			<?php foreach ($lists as $list): ?>
				<optgroup label="<?php echo __('List:','cleverreach').' '.$list->name; ?>">
					<?php foreach($list->forms as $form): ?>
						<option value="<?php echo $list->id.'-'.$form->id ?>" <?php echo (isset( $selected_value ) && $selected_value == $list->id.'-'.$form->id?'selected':''); ?>>
							<?php echo $form->name; ?>
						</option>
					<?php endforeach; ?>
				</optgroup>
			<?php endforeach; ?>
		<?php
	}

	public function field_api_key(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[api_key]' type='text' value='<?php echo (isset($options['api_key'])?$options['api_key']:''); ?>' class="widefat"/>
		<p class="notice notice-error"><?php _e( 'The API key is still available for backwards compatibility but you should switch to the new REST API using the button below.', 'cleverreach' ); ?></p>
		<?php		
	}


	public function field_token(){
		$options = haet_cleverreach_get_settings();

		$clientid = "L1Qut1PNTT";
		$clientsecret = "DcTpFivGkTK5x5760q64hR58mzacm45Z";
		$auth_url = "https://rest.cleverreach.com/oauth/authorize.php";
		$token_url = "https://rest.cleverreach.com/oauth/token.php";

		$rdu = urlencode( admin_url( 'admin.php?page=cleverreach&cleverreach-athenticate=1' ) ); 
		$message = ''; 

		if( isset( $_GET["cleverreach-athenticate"] ) && isset( $_GET["code"] ) ) {  
		    //prepare post
		    $fields["client_id"]=$clientid;
		    $fields["client_secret"]=$clientsecret;
		    
		    //must be the same as previous redirect uri
		    $fields["redirect_uri"] = urldecode($rdu);    
		    
		    //tell oauth what we want! we want to trade in our auth code for an access token
		    $fields["grant_type"]="authorization_code"; 
		    // $fields["scope"]="write"; 
		    $fields["code"]=$_GET["code"];  


		    //Trade the Authorize token for an access token
		    $curl = curl_init();
		    curl_setopt($curl,CURLOPT_URL, $token_url);
		    curl_setopt($curl,CURLOPT_POST, sizeof($fields));
		    curl_setopt($curl,CURLOPT_POSTFIELDS, $fields);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		    $result = curl_exec($curl); //
		    curl_close ($curl);
		    $result = json_decode( $result );

		    
		    if( isset( $result->error ) )
		        $message = '<div class="error"><p>' . $result->error_description . ' [' . $result->error . ']</p></div>';
		    elseif( isset( $result->access_token ) ){
		        $options['token'] = $result->access_token;
		    }
		}



		
		if( isset($options['api_key']) && !isset($options['token']) ): ?>
			<p><strong><?php _e( 'You can switch to the new faster REST API at any time, just connect your account below.', 'cleverreach' ); ?></strong></p>
		<?php endif; ?>

		
		<p class="description">
			<?php if( $options['token'] ): ?>
				<?php _e( 'Your CleverReach account is connected.', 'cleverreach' ); ?>
				<a href="<?php echo $auth_url . '?client_id=' . $clientid . '&grant=write&response_type=code&redirect_uri=' . $rdu; ?>" class="button"><?php _e( 'Reconnect', 'cleverreach' ); ?></a>
			<?php else: ?>
				<a href="<?php echo $auth_url . '?client_id=' . $clientid . '&grant=write&response_type=code&redirect_uri=' . $rdu; ?>" class="button"><?php _e( 'Connect to CleverReach', 'cleverreach' ); ?></a>
			<?php endif; ?>
			<?php echo $message; // . ( isset( $result ) ? '<pre>'.print_r($result,true).'</pre>' : '' );?>
		</p>
		<input name='haet_cleverreach_settings[token]' id="haet_cleverreach_settings_token" type='hidden' value='<?php echo (isset($options['token'])?$options['token']:''); ?>' class="widefat"/>
		<?php $redirect = 'admin.php?page=cleverreach';  ?>
		<input type="hidden" name="_wp_http_referer" value="<?php echo $redirect; ?>">
		<?php		
	}


	/**
	 *	Comments integration
	 */
	public function field_show_in_comments(){
		$options = haet_cleverreach_get_settings();?>
		<input name='haet_cleverreach_settings[show_in_comments]' type='radio' value='1' <?php echo ( intval( $options['show_in_comments'] ) == 1?'checked':''); ?> /> <?php _e('Yes'); ?> &nbsp; &nbsp;
		<input name='haet_cleverreach_settings[show_in_comments]' type='radio' value='0' <?php echo ( intval( $options['show_in_comments'] ) != 1?'checked':''); ?> /> <?php _e('No'); ?>
		<?php		
	}

	public function field_show_in_comments_caption(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[show_in_comments_caption]' type='text' value='<?php echo $options['show_in_comments_caption']; ?>' />
		<?php		
	}

	public function field_show_in_comments_form(){
		$options = haet_cleverreach_get_settings();
		if( isset( $options['lists'] ) && is_array( $options['lists'] ) ): 
			if( defined( 'ICL_LANGUAGE_CODE' ) ): //WPML is active
				$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
			    if ( !empty( $languages ) ):
			        foreach( $languages as $language ): ?>
		        		<img src="<?php echo $language['country_flag_url']; ?>" height="12" alt="<?php echo $language['language_code'];?>" width="18" />
    					<select name="haet_cleverreach_settings[show_in_comments_form_<?php echo $language['language_code']; ?>]">
    						<?php $this->field_helper_show_options_form($options['lists'],$options['show_in_comments_form_'.$language['language_code']]) ?>
    					</select><br>
        				<?php
			        endforeach;
			    endif;
			else: ?>
				<select name="haet_cleverreach_settings[show_in_comments_form]">
					<?php $this->field_helper_show_options_form($options['lists'],$options['show_in_comments_form']) ?>
				</select>
			<?php endif;
		endif;		
	}

	public function field_show_in_comments_name_attribute(){
		$settings = haet_cleverreach_get_settings();
		if( isset($settings['token']) && $settings['token'] != '' ):

			$api = new haet_cleverreach_api_rest( $settings['token'] );

			if( defined( 'ICL_LANGUAGE_CODE' ) ): //WPML is active
				$languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
			    if ( !empty( $languages ) ):
			        foreach( $languages as $language ): ?>
		        		<img src="<?php echo $language['country_flag_url']; ?>" height="12" alt="<?php echo $language['language_code'];?>" width="18" />
	    				<?php
	    				$list_form = $settings['show_in_comments_form_'.$language['language_code']];
	    				$list_form_array = explode('-', $list_form);
	    				$form_id = $list_form_array[1];
	    				$list_id = $list_form_array[0];

	    				$attributes_result = $api->get_list_attributes($list_id);

	    				if( $attributes_result['success'] ):
	    					$attributes = $attributes_result['attributes'];
	    					?>
	    					<select name="haet_cleverreach_settings[show_in_comments_name_attribute_<?php echo $language['language_code']; ?>]">
	    						<?php if( is_array($attributes_result['global_attributes']) && count( $attributes_result['global_attributes'] ) > 0 ): ?>
	    							<optgroup label="<?php _e('Global atributes','cleverreach'); ?>">
	    								<?php
	    								foreach ($attributes_result['global_attributes'] as $attribute): ?>
	    								    <?php if( $attribute->type == 'text' ): ?>
	    								    	<option value="<?php echo $attribute->name; ?>" <?php echo (isset($settings['show_in_comments_name_attribute_'.$language['language_code']]) && $settings['show_in_comments_name_attribute_'.$language['language_code']]==$attribute->name?'selected':'' ); ?>>
	    								    		<?php echo $attribute->description; ?>
	    								    	</option>
	    								    <?php endif; 
	    								endforeach; 
	    								?>
	    							</optgroup>
	    						<?php endif; ?>
	    						<?php if( is_array($attributes_result['list_attributes']) && count( $attributes_result['list_attributes'] ) > 0 ): ?>
	    							<optgroup label="<?php _e('List atributes','cleverreach'); ?>">
	    								<?php
	    								foreach ($attributes_result['list_attributes'] as $attribute): ?>
	    								    <?php if( $attribute->type == 'text' ): ?>
	    								    	<option value="<?php echo $attribute->name; ?>" <?php echo (isset($settings['show_in_comments_name_attribute_'.$language['language_code']]) && $settings['show_in_comments_name_attribute_'.$language['language_code']]==$attribute->name?'selected':'' ); ?>>
	    								    		<?php echo $attribute->description; ?>
	    								    	</option>
	    								    <?php endif; 
	    								endforeach; 
	    								?>
	    							</optgroup>
	    						<?php endif; ?>
	    					</select><br>
	    					<?php
	    				else:
	    					_e( 'Please select a CleverReach form above.', 'haet_cleverreachwoocommerce' ); 
	    					echo '<br>';
	    				endif;
			        endforeach;
			    endif;
			else:
				$list_form = $settings['show_in_comments_form'];
				$list_form_array = explode('-', $list_form);
				$form_id = $list_form_array[1];
				$list_id = $list_form_array[0];

				$attributes_result = $api->get_list_attributes($list_id);

				if( $attributes_result['success'] ):
					?>
					<select name="haet_cleverreach_settings[show_in_comments_name_attribute]">
						<?php if( is_array($attributes_result['global_attributes']) && count( $attributes_result['global_attributes'] ) > 0 ): ?>
							<optgroup label="<?php _e('Global atributes','cleverreach'); ?>">
								<?php
								foreach ($attributes_result['global_attributes'] as $attribute): ?>
								    <?php if( $attribute->type == 'text' ): ?>
								    	<option value="<?php echo $attribute->name; ?>" <?php echo (isset($settings['show_in_comments_name_attribute']) && $settings['show_in_comments_name_attribute']==$attribute->name?'selected':'' ); ?>>
								    		<?php echo $attribute->description; ?>
								    	</option>
								    <?php endif; 
								endforeach; 
								?>
							</optgroup>
						<?php endif; ?>
						<?php if( is_array($attributes_result['list_attributes']) && count( $attributes_result['list_attributes'] ) > 0 ): ?>
							<optgroup label="<?php _e('List atributes','cleverreach'); ?>">
								<?php
								foreach ($attributes_result['list_attributes'] as $attribute): ?>
								    <?php if( $attribute->type == 'text' ): ?>
								    	<option value="<?php echo $attribute->name; ?>" <?php echo (isset($settings['show_in_comments_name_attribute']) && $settings['show_in_comments_name_attribute']==$attribute->name?'selected':'' ); ?>>
								    		<?php echo $attribute->description; ?>
								    	</option>
								    <?php endif; 
								endforeach; 
								?>
							</optgroup>
						<?php endif; ?>
					</select>
					<?php
				else:
					_e( 'Please select a CleverReach form above.', 'haet_cleverreachwoocommerce' ); 
					echo '<br>';
				endif;
			endif;
			?>
			<p class="notice list-change-notice">
				<?php _e( 'Please save your form changes first in order to refresh available attributes.', 'haet_cleverreachwoocommerce' ); ?>
			</p>
			<p class="description">
				<?php _e( 'Select a CleverReach attribute to store the name of the comment author.', 'haet_cleverreachwoocommerce' ); ?>
			</p>
			<?php
		endif;
	}

	public function field_show_in_comments_defaultchecked(){
		$options = haet_cleverreach_get_settings();?>
		<input name='haet_cleverreach_settings[show_in_comments_defaultchecked]' type='radio' value='1' <?php echo ( intval( $options['show_in_comments_defaultchecked'] ) == 1?'checked':''); ?> /> <?php _e('Yes'); ?> &nbsp; &nbsp;
		<input name='haet_cleverreach_settings[show_in_comments_defaultchecked]' type='radio' value='0' <?php echo ( intval( $options['show_in_comments_defaultchecked'] ) != 1?'checked':''); ?> /> <?php _e('No'); ?>
		<p class="description">
			<?php _e( 'Please make sure this option is allowed in your country.', 'cleverreach' ); ?>
		</p>
		<?php		
	}

	/**
	 *	Registration integration
	 */
	public function field_show_at_registration(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[show_at_registration]' type='radio' value='1' <?php echo ( intval( $options['show_at_registration'] ) == 1?'checked':''); ?> /> <?php _e('Yes'); ?> &nbsp; &nbsp;
		<input name='haet_cleverreach_settings[show_at_registration]' type='radio' value='0' <?php echo ( intval( $options['show_at_registration'] ) != 1?'checked':''); ?> /> <?php _e('No'); ?>
		<?php		
	}

	public function field_show_at_registration_caption(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[show_at_registration_caption]' type='text' value='<?php echo $options['show_at_registration_caption']; ?>' />
		<?php		
	}

	public function field_show_at_registration_form(){
		$options = haet_cleverreach_get_settings();
		if( isset( $options['lists'] ) && is_array( $options['lists'] ) ): ?>
			<select name="haet_cleverreach_settings[show_at_registration_form]">
				<?php $this->field_helper_show_options_form($options['lists'],$options['show_at_registration_form']) ?>
			</select>
		<?php endif;		
	}


	public function field_show_at_registration_defaultchecked(){
		$options = haet_cleverreach_get_settings();?>
		<input name='haet_cleverreach_settings[show_at_registration_defaultchecked]' type='radio' value='1' <?php echo ( intval( $options['show_at_registration_defaultchecked'] ) == 1?'checked':''); ?> /> <?php _e('Yes'); ?> &nbsp; &nbsp;
		<input name='haet_cleverreach_settings[show_at_registration_defaultchecked]' type='radio' value='0' <?php echo ( intval( $options['show_at_registration_defaultchecked'] ) != 1?'checked':''); ?> /> <?php _e('No'); ?>
		<?php		
	}


	/**
	 *	Form builder
	 */
	public function field_message_success(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[message_success]' class="widefat" type='text' value='<?php echo esc_attr($options['message_success']); ?>' />
		<?php		
	}

	public function field_message_entry_exists(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[message_entry_exists]' class="widefat" type='text' value='<?php echo esc_attr($options['message_entry_exists']); ?>' />
		<?php		
	}

	public function field_message_error(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[message_error]' class="widefat" type='text' value='<?php echo esc_attr($options['message_error']); ?>' />
		<?php		
	}

	public function field_message_invalid_email(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[message_invalid_email]' class="widefat" type='text' value='<?php echo esc_attr($options['message_invalid_email']); ?>' />
		<?php		
	}

	public function field_message_required_field(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[message_required_field]' class="widefat" type='text' value='<?php echo esc_attr($options['message_required_field']); ?>' />
		<?php		
	}

	public function field_attributes_available(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[attributes_available]' type='hidden' value='<?php echo ( isset($options['attributes_available'])?esc_attr($options['attributes_available']):''); ?>' />
		<?php		
	}

	public function field_attributes_used(){
		$options = haet_cleverreach_get_settings();
		?>
		<input name='haet_cleverreach_settings[attributes_used]' type='hidden' value='<?php echo ( isset($options['attributes_used'])?esc_attr($options['attributes_used']):''); ?>' />
		<?php		
	}

	public function field_label_position(){
		$options = haet_cleverreach_get_settings();
		$availabe_options = array(
			'left'	=>  __('Left of field','cleverreach'),
			'top'	=>  __('Above field','cleverreach'),
			'right'	=>  __('Right of field','cleverreach'),
			'inside'	=>  __('Inside field','cleverreach')
		);?>
		<select name="haet_cleverreach_settings[label_position]">
			<?php foreach ($availabe_options as $val => $label):?>
			    <option value="<?php echo $val; ?>" <?php echo ($options['label_position']==$val?'selected':'') ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
		</select>
		<?php 
	}
}


?>