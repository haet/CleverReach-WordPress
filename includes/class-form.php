<?php

class haet_cleverreach_form {

    public function __construct() {
        add_action( 'init', array( $this,'process_form' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'form_scripts_and_styles' ) );
        add_action( 'wp_ajax_cleverreach_submit', array( $this, 'ajax_submit' ) );
        add_action( 'wp_ajax_nopriv_cleverreach_submit', array( $this, 'ajax_submit' ) );
        add_shortcode( 'cleverreach_signup', array( $this, 'register_shortcode' ) );
    }


    /**
     *  Register Shortcode [cleverreach_signup]
     */
    function register_shortcode( $atts ){
        ob_start();
        $this->show_form(false);
        return ob_get_clean();
        
    }


    /**
     *  Load Frontent JS and CSS
     */
    public function form_scripts_and_styles($page){
        wp_enqueue_script('haet_cleverreach_script',  HAET_CLEVERREACH_URL.'/js/form.js', array( 'jquery'), '', true );
        wp_localize_script( 'haet_cleverreach_script', 'ajax_object',
            array( 
                'ajax_url'  => admin_url( 'admin-ajax.php' )
            ) 
        );
        wp_enqueue_style('haet_cleverreach_style',  HAET_CLEVERREACH_URL.'/css/frontend.css');
    }


    /**
     *  Process the submitted form
     */
    public function process_form($submission=null){
        if( !$submission )
            $submission = $_POST;
        
        if( isset($submission['haet-cleverreach-form-id']) && isset($submission['haet-cleverreach-list-id']) ){
            $settings = haet_cleverreach_get_settings();
            if(isset( $settings['attributes_used'] )){
                $attributes = json_decode( $settings['attributes_used'] );
                $validation = new stdClass();
                $validation->is_widget = $submission['haet-cleverreach-is-widget'];
                $validation->valid = true;
                $validation->message = $settings['message_success'];

                foreach( $attributes as $attribute){
                    if( in_array( $attribute->type, array('text', 'email', 'gender')) ){
                        if ( isset( $submission['haet-cleverreach-'.$attribute->field] ) ){
                            $valid = true;
                            $error = '';
                            //validate email
                            if( $attribute->type == 'email' ){
                                if(  $submission['haet-cleverreach-'.$attribute->field] == '' ){
                                    $valid = false;
                                    $validation->valid = false;
                                    $error = $settings['message_required_field'];
                                    $validation->message = $settings['message_invalid_email'];
                                }else if(  !is_email( $submission['haet-cleverreach-'.$attribute->field] ) ){
                                    $valid = false;
                                    $validation->valid = false;
                                    $error = $settings['message_invalid_email'];
                                    $validation->message = $settings['message_invalid_email'];
                                }

                            }
                            
                            $validation->fields[$attribute->field] = new stdClass();
                            $validation->fields[$attribute->field]->valid = $valid;
                            $validation->fields[$attribute->field]->value = $submission['haet-cleverreach-'.$attribute->field];
                            $validation->fields[$attribute->field]->error = $error;
                        }else{
                            //field missing
                            $validation->message = $settings['message_invalid_email'];
                        }
                    }
                }

                if( $validation->valid ){
                    if( isset($settings['api_key']) && $settings['api_key'] != '' ){
                        $form_id = $settings['signup_form_id'];
                        $list_id = $settings['signup_list_id'];
                        $api = new haet_cleverreach_api( $settings['api_key'] );
                        if( $validation->is_widget )
                            $source = get_bloginfo('name').' (Widget)';
                        else
                            $source = get_bloginfo('name').' (Newsletter form)';
                        $subscription_result = $api->subscribe_user($settings, $validation->fields, $form_id, $list_id, $source);
                        //echo '<pre>'.print_r($subscription_result,true).'</pre>';
                        if( !$subscription_result['success'] ){
                            $validation->valid = false;
                            $validation->message = $subscription_result['message'];
                        }
                    }else{
                        $validation->valid = false;
                        $validation->message = $settings['message_error'];
                    }

                }
                if(!session_id()) {
                    session_start();
                }
                $_SESSION['haet_cleverreach_validation'] = $validation;
            }
        }
    }



    /**
     *  Output the form
     */
    public function show_form($is_widget = false){
        ?>
        <div class="haet-cleverreach">
            <?php
            $settings = haet_cleverreach_get_settings();
            if( session_id() && isset($_SESSION['haet_cleverreach_validation'])) {
                $validation = $_SESSION['haet_cleverreach_validation'];
                // if two forms are displayed on the same page (sidebar && content) assign validation to the correct form
                if($validation->is_widget == 1 && $is_widget || $validation->is_widget == 0 && !$is_widget){
                    unset($_SESSION['haet_cleverreach_validation']);
                    if( strlen( $validation->message )>0 ){
                        echo '<p class="'.($validation->valid?'message-success':'message-error').'">'.$validation->message.'</p>';
                    }
                }else{
                    unset( $validation );
                }
            }
            if( !isset($validation) || !$validation->valid ){
            
                if(isset( $settings['attributes_used'] )){
                    $attributes = json_decode( $settings['attributes_used'] );
                    $label_position = ( isset( $settings['label_position'] )?$settings['label_position']:'left');
                    if( is_array($attributes) && $settings['signup_form_id'] != '' && $settings['signup_list_id'] != ''){
                        //echo '<pre>'.print_r($attributes,true).'</pre>';
                        ?>
                        <form method="post" class="haet-cleverreach-form">
                            <input type="hidden" name="haet-cleverreach-is-widget" value="<?php echo ($is_widget?'1':'0');?>">
                            <input type="hidden" name="haet-cleverreach-form-id" value="<?php echo $settings['signup_form_id'];?>">
                            <input type="hidden" name="haet-cleverreach-list-id" value="<?php echo $settings['signup_list_id'];?>">
                            <?php foreach( $attributes as $attribute){
                                $this->show_field($attribute, $label_position,(isset($validation)?$validation:null));
                            }?>
                        </form>
                        <?php
                    }
                }
            }?>
        </div>
        <?php
    }



    /**
     *  Display a single field
     */
    private function show_field($attribute, $label_position, $validation=null){
        $field_has_error = isset($validation) && isset( $validation->fields[$attribute->field] ) && !$validation->fields[$attribute->field]->valid;
        ?>
        <div class="haet-cleverreach-field-wrap label-<?php echo $label_position; ?> type-<?php echo $attribute->type; ?> <?php echo ($field_has_error?'field-error':''); ?>" >
            <?php if( $attribute->type == 'description'): ?>
                <p><?php echo nl2br($attribute->label); ?></p>
            <?php elseif( $attribute->type == 'submit'): ?>
                <button type="submit" class="button" id="haet-cleverreach-submit">
                    <?php echo $attribute->label; ?>
                </button>
            <?php else: ?>
                <?php if($label_position != 'inside'): ?>
                    <label for="haet-cleverreach-<?php echo $attribute->field; ?>">
                        <?php echo $attribute->label; ?>
                    </label>
                <?php endif; ?>
                <?php if( $attribute->type == 'text' || $attribute->type == 'email'): ?>       
                    <input 
                        type="<?php echo $attribute->type; ?>" 
                        id="haet-cleverreach-<?php echo $attribute->field; ?>" 
                        name="haet-cleverreach-<?php echo $attribute->field; ?>"
                        value="<?php echo (isset($validation) && isset($validation->fields[$attribute->field]->value)?$validation->fields[$attribute->field]->value:''); ?>"
                        <?php echo (($label_position == 'inside') ? ' placeholder="'.esc_attr($attribute->label).'" ':''); ?>
                    >
                <?php elseif( $attribute->type == 'gender' ): 
                    $field_options = explode("\n",$attribute->options);
                    ?>
                    <select id="haet-cleverreach-<?php echo $attribute->field; ?>" name="haet-cleverreach-<?php echo $attribute->field; ?>">
                        <?php if($label_position == 'inside'): ?>
                            <option value=""><?php echo $attribute->label; ?></option>
                        <?php endif; ?>
                        <?php foreach ($field_options as $option):?>
                            <option><?php echo $option; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <?php if(   isset($validation) 
                            && isset($validation->fields[$attribute->field]) 
                            && !$validation->fields[$attribute->field]->valid): ?>
                    <p class="cleverreach-error-message">
                        <?php echo $validation->fields[$attribute->field]->error; ?>
                    </p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        <?php
    }



    /**
     *  Form submitted via AJAX
     */
    public function ajax_submit(){
        if( isset( $_POST['submission'] ) ){
            $submission = $_POST['submission'];
            $this->process_form($submission);
            $this->show_form($_POST['submission']['haet-cleverreach-is-widget']);
        }
        wp_die();
    }
}