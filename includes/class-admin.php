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
		register_setting( 'haet_cleverreach_option_group', 'haet_cleverreach_settings', array( $this, 'validate_settings' ) );
		// page general
		add_settings_section( 
				'general_settings', 
				__('General settings','haet_cleverreach' ), 
				array($this,'section_intro'), 
				'toplevel_page_cleverreach'
			);
		add_settings_field( 
				'api_key', 
				__( 'CleverReach API key', 'haet_cleverreach' ), 
				array($this,'field_api_key'), 
				'toplevel_page_cleverreach', 
				'general_settings'
			);

		// page integrations
		// section comments
		add_settings_section( 
				'integration_comments', 
				__('Comment Form Integration','haet_cleverreach' ), 
				array($this,'section_intro'), 
				'cleverreach_page_cleverreach-integrations'
			);
		add_settings_field( 
				'show_in_comments', 
				__( 'Show checkbox in comments', 'haet_cleverreach' ), 
				array($this,'field_show_in_comments'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);
		add_settings_field( 
				'show_in_comments_caption', 
				__( 'Checkbox caption', 'haet_cleverreach' ), 
				array($this,'field_show_in_comments_caption'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);
		add_settings_field( 
				'show_in_comments_form', 
				__( 'CleverReach form', 'haet_cleverreach' ), 
				array($this,'field_show_in_comments_form'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);
		add_settings_field( 
				'show_in_comments_defaultchecked', 
				__( 'Checked by default', 'haet_cleverreach' ), 
				array($this,'field_show_in_comments_defaultchecked'), 
				'cleverreach_page_cleverreach-integrations', 
				'integration_comments'
			);

		// section registration
		// add_settings_section( 
		// 		'integration_registration', 
		// 		__('Registration Integration','haet_cleverreach' ), 
		// 		array($this,'section_intro'), 
		// 		'cleverreach_page_cleverreach-integrations'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration', 
		// 		__( 'Show checkbox at registration', 'haet_cleverreach' ), 
		// 		array($this,'field_show_at_registration'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration_caption', 
		// 		__( 'Checkbox caption', 'haet_cleverreach' ), 
		// 		array($this,'field_show_at_registration_caption'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration_form', 
		// 		__( 'CleverReach form', 'haet_cleverreach' ), 
		// 		array($this,'field_show_at_registration_form'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		// add_settings_field( 
		// 		'show_at_registration_defaultchecked', 
		// 		__( 'Checked by default', 'haet_cleverreach' ), 
		// 		array($this,'field_show_at_registration_defaultchecked'), 
		// 		'cleverreach_page_cleverreach-integrations', 
		// 		'integration_registration'
		// 	);
		
		// page form
		add_settings_section( 
				'form_settings', 
				__('Form Settings','haet_cleverreach' ), 
				array($this,'section_intro'), 
				'cleverreach_page_cleverreach-forms'
			);
		add_settings_field( 
				'message_success', 
				__( 'Success message', 'haet_cleverreach' ), 
				array($this,'field_message_success'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_invalid_email', 
				__( 'Invalid Email', 'haet_cleverreach' ), 
				array($this,'field_message_invalid_email'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_entry_exists', 
				__( 'Already subscribed', 'haet_cleverreach' ), 
				array($this,'field_message_entry_exists'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_error', 
				__( 'An Error occured', 'haet_cleverreach' ), 
				array($this,'field_message_error'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'message_required_field', 
				__( 'Required Field Error', 'haet_cleverreach' ), 
				array($this,'field_message_required_field'), 
				'cleverreach_page_cleverreach-forms', 
				'form_settings'
			);
		add_settings_field( 
				'label_position', 
				__( 'Label position', 'haet_cleverreach' ), 
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
		$text_fields = array( 'api_key', 'redirect', 'css' );

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
			__('CleverReach Form Integrations','haet_cleverreach'),
			__('Integrations','haet_cleverreach'),
			apply_filters( 'haet_cleverreach_capabilities', 'manage_options' ),
			'cleverreach-integrations',
			array( $this, 'show_settings_integrations' )
		);

		add_submenu_page(
			'cleverreach',
			__('Form Builder','haet_cleverreach'),
			__('Form Builder','haet_cleverreach'),
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
	        wp_localize_script( 'haet_cleverreach_admin_script', 'ajax_object',
	                    array( 
	                    	'ajax_url' 	=> admin_url( 'admin-ajax.php' ),
	                    	'translations'	=> array(
	                    	 	'label'	=>	__( 'Label', 'haet_cleverreach' ),
	                    	 	'text'	=>	__( 'Text', 'haet_cleverreach' ),
	                    	 	'available_options'	=>	__( 'Available Options', 'haet_cleverreach' ),
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
		$api_message = __('Please enter your API key to connect to your CleverReach account.<br><br>If you don\'t have an account yet <a href="http://www.cleverreach.com/?rk=74089ojcjrsrb" target="_blank">SIGNUP HERE</a> for a free newsletter system for up to 250 receivers.','haet_cleverreach');
		$refresh_lists = false;
		$list_result = array();

		if( isset($settings['api_key']) && $settings['api_key'] != '' ){
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
			if( isset($settings['api_key']) && $settings['api_key'] != '' ){
				$api = new haet_cleverreach_api( $settings['api_key'] );
				$list_form = $_POST['haet_cleverreach_get_fields'];
				$list_form_array = explode('-', $list_form);
				$form_id = $list_form_array[1];
				// $form_result = $api->get_form_code($form_id);
				// if( $form_result['success'] )
				// 	$settings['form_code'] = $form_result['code'];

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
				<optgroup label="<?php echo __('List:','haet_cleverreach').' '.$list->name; ?>">
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
		<p class="description"><?php _e( 'You can find the API key in your <a href="https://eu1.cleverreach.com/admin/account_api.php?rk=74089ojcjrsrb" target="_blank">CleverReach account settings</a>', 'haet_cleverreach' ); ?></p>
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
		if( isset( $options['lists'] ) && is_array( $options['lists'] ) ): ?>
			<select name="haet_cleverreach_settings[show_in_comments_form]">
				<?php $this->field_helper_show_options_form($options['lists'],$options['show_in_comments_form']) ?>
			</select>
		<?php endif;		
	}

	public function field_show_in_comments_defaultchecked(){
		$options = haet_cleverreach_get_settings();?>
		<input name='haet_cleverreach_settings[show_in_comments_defaultchecked]' type='radio' value='1' <?php echo ( intval( $options['show_in_comments_defaultchecked'] ) == 1?'checked':''); ?> /> <?php _e('Yes'); ?> &nbsp; &nbsp;
		<input name='haet_cleverreach_settings[show_in_comments_defaultchecked]' type='radio' value='0' <?php echo ( intval( $options['show_in_comments_defaultchecked'] ) != 1?'checked':''); ?> /> <?php _e('No'); ?>
		<p class="description">
			<?php _e( 'Please make sure this option is allowed in your country.', 'haet_cleverreach' ); ?>
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
			'left'	=>  __('Left of field','haet_cleverreach'),
			'top'	=>  __('Above field','haet_cleverreach'),
			'right'	=>  __('Right of field','haet_cleverreach'),
			'inside'	=>  __('Inside field','haet_cleverreach')
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