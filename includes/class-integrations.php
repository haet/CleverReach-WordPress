<?php

class haet_cleverreach_integrations {

    public function __construct() {
        //add_action( 'comment_form_logged_in_after', array($this,'show_checkbox_comments') );
        add_action( 'comment_form', array($this,'show_checkbox_comments') );
        add_action( 'comment_post', array( $this, 'process_form_comment' ), 40, 2 );
    }

    function show_checkbox_comments(){
        $settings = haet_cleverreach_get_settings();
        if( isset($settings['show_in_comments']) && $settings['show_in_comments']==1 ){
            ?>
            <p class="cleverreach-checkbox cleverreach-checkbox-comments">
                <input type="checkbox" name="cleverreach_checkbox_comments" id="cleverreach_checkbox_comments" <?php echo (isset($settings['show_in_comments_defaultchecked']) && $settings['show_in_comments_defaultchecked']==1?'checked':''); ?> value="1"/>
                <label for="cleverreach_checkbox_comments"><?php echo $settings['show_in_comments_caption']; ?></label>
            </p>
            <?php
        }
    }




    public function process_form_comment( $comment_id, $comment_approved = '' ) {
        $settings = haet_cleverreach_get_settings();

        if ( !isset($_POST['cleverreach_checkbox_comments']) || intval($_POST['cleverreach_checkbox_comments']) != 1 ) {
            return false;
        }

        if ( $comment_approved === 'spam' ) {
            return false;
        }

        $comment = get_comment( $comment_id );

        $fields = array();
        $fields['cleverreach_email'] = new stdClass();
        $fields['cleverreach_email']->value = $comment->comment_author_email;

        // SOAP 
        if( isset($settings['api_key']) && $settings['api_key'] != '' ){
            $api = new haet_cleverreach_api( $settings['api_key'] );
            $fields['firstname'] = new stdClass();
            $fields['firstname']->value = $comment->comment_author;
            $list_form = $settings['show_in_comments_form'];
        }
        if( isset($settings['token']) && $settings['token'] != '' ){
            if( defined( 'ICL_LANGUAGE_CODE' ) ){ //WPML is active
                $attribute = $settings['show_in_comments_name_attribute_'.ICL_LANGUAGE_CODE];
                $list_form = $settings['show_in_comments_form_'.ICL_LANGUAGE_CODE];
            }else{
                $attribute = $settings['show_in_comments_name_attribute'];
                $list_form = $settings['show_in_comments_form'];
            }

            $fields[$attribute] = new stdClass();
            $fields[$attribute]->value = $comment->comment_author;
            $api = new haet_cleverreach_api_rest( $settings['token'] );
        }

        if( isset( $api ) ){
            
            $list_form_array = explode('-', $list_form);
            $form_id = $list_form_array[1];
            $list_id = $list_form_array[0];
            $source = get_bloginfo('name').' (Comments)';
            $api->subscribe_user($settings, $fields, $form_id, $list_id, $source);
        }
    }
}