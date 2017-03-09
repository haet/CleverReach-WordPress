<div id="haet-cleverreach-settings-form" class="wrap haet-cleverreach-settings">
	<h2><img src="<?php echo HAET_CLEVERREACH_URL . 'images/logo.png'; ?>" /> <?php _e( 'Form Builder', 'haet_cleverreach' ); ?></h2>
    
    <?php if( isset( $attributes ) || isset($settings['attributes_available'])  || isset($settings['attributes_used']) ): ?>
    	<form action="options.php" method="post">
    		
            <?php /*
            <textarea><?php echo $settings['form_code']; ?></textarea>
            
    		<?php submit_button(); ?>
            <pre>
            <?php print_r($attributes); ?>
            </pre>*/ ?>
            <h3><?php _e('Drag & Drop fields to create your form','haet_cleverreach'); ?></h3>
            <div class="clearfix">
                <div class="sortable-wrapper">
                    <h4><?php _e('Your Form','haet_cleverreach'); ?></h4>
                    <ul id="haet_cleverreach_formfields_used" class="connected-sortable">
                        
                    </ul>
                </div>
                <div class="sortable-wrapper">
                    <h4><?php _e('Available Fields','haet_cleverreach'); ?></h4>
                    <ul id="haet_cleverreach_formfields_available" class="connected-sortable">
                        <?php if( isset( $attributes ) && is_array( $attributes ) ): // Data refreshed ?>
                            <li id="cleverreach-attribute-description" data-key="cleverreach_description" data-type="description" class="attribute clearfix type-description">

                                <span class="attribute-name">
                                    <span class="dashicons dashicons-editor-alignleft"></span>
                                    <?php _e( 'Description Text', 'haet_cleverreach' ); ?>
                                </span>
                                <div class="field-description">
                                    <label><?php _e( 'Text', 'haet_cleverreach' ); ?></label>
                                    <textarea><?php _e( 'Signup for our Newsletter!', 'haet_cleverreach' ); ?></textarea>
                                </div>
                            </li>
                            <li id="cleverreach-attribute-email" data-key="cleverreach_email" data-type="email" class="attribute clearfix type-email">
                                <span class="attribute-name">
                                    <span class="dashicons dashicons-email"></span>
                                    <?php _e( 'Email Address', 'haet_cleverreach' ); ?>
                                </span>
                                <div class="field-label">
                                    <label><?php _e( 'Label', 'haet_cleverreach' ); ?></label>
                                    <input type="text" value="<?php _e( 'Email', 'haet_cleverreach' ); ?>">
                                </div>
                            </li>

                            <?php foreach ($attributes as $attribute): 
                                $icon = '<span class="dashicons dashicons-marker"></span>';
                                if( $attribute->type == 'gender' )
                                    $icon = '<span class="dashicons dashicons-universal-access"></span>';
                                if( $attribute->type == 'text' )
                                    $icon = '<span class="dashicons dashicons-feedback"></span>';
                                ?>
                                <li id="cleverreach-attribute-<?php echo $attribute->key; ?>" data-key="<?php echo $attribute->key; ?>" data-type="<?php echo $attribute->type; ?>" class="attribute clearfix type-<?php echo $attribute->type; ?>">
                                    <span class="attribute-name">
                                        <?php echo $icon.' '.$attribute->key; ?>
                                    </span>
                                    <div class="field-label">
                                        <label><?php _e( 'Label', 'haet_cleverreach' ); ?></label>
                                        <input type="text" value="<?php echo ucfirst( str_replace('_', ' ', $attribute->key) ); ?>">
                                    </div>
                                    <?php if( $attribute->type == 'gender' ): ?>
                                        <div class="field-options">
                                            <label><?php _e( 'Available Options', 'haet_cleverreach' ); ?></label>
                                            <textarea><?php 
                                                echo    __( 'Mrs.', 'haet_cleverreach' )."\n".
                                                        __( 'Mr.', 'haet_cleverreach' )."\n".
                                                        __( 'Family', 'haet_cleverreach' )."\n".
                                                        __( 'Company', 'haet_cleverreach' );
                                            ?></textarea>
                                        </div>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>

                            <li id="cleverreach-attribute-submit_button" data-key="cleverreach_submit_button" data-type="submit"  class="attribute clearfix type-submit">
                                <span class="attribute-name">
                                    <span class="dashicons dashicons-yes"></span>
                                    <?php _e( 'Submit Button', 'haet_cleverreach' ); ?>
                                </span>
                                <div class="field-label">
                                    <label><?php _e( 'Label', 'haet_cleverreach' ); ?></label>
                                    <input type="text" value="<?php _e( 'Subscribe', 'haet_cleverreach' ); ?>">
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php settings_fields( 'haet_cleverreach_option_group' ); ?>
            <?php do_settings_sections('cleverreach_page_cleverreach-forms'); ?>
            <?php submit_button(); ?>
        </form>
        <br><hr><br>
        <p>
            <?php _e( 'Delete this form and reload attributes from CleverReach?', 'haet_cleverreach' ); ?>
        </p>
    <?php else: ?>
        
    <?php endif; ?>
    <p>
        <?php _e( 'Please select a form below to load the attributes from CleverReach.', 'haet_cleverreach' ); ?>
    </p>
	<form method="post">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e( 'CleverReach form', 'haet_cleverreach' ); ?></th>
                    <td>   
                        <?php if( isset( $settings['lists'] ) && is_array( $settings['lists'] ) ): ?>
                            <select name="haet_cleverreach_get_fields">
                                <?php $this->field_helper_show_options_form($settings['lists'],'') ?>
                            </select>
                        <?php endif; ?>    
                    </td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td>   
                        <input type="submit" value="<?php _e( 'Load Form Attributes', 'haet_cleverreach' ); ?>" class="button" />  
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    
</div>
<div class="haet-cleverreach-sidebar">
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-ninja.php'; ?>
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-form-usage.php'; ?>
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-woocommerce.php'; ?>
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-haet.php'; ?>
</div>