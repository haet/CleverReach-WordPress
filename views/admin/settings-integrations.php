<div id="haet-cleverreach-settings-integrations" class="wrap haet-cleverreach-settings">

	<h2><img src="<?php echo HAET_CLEVERREACH_URL . 'images/logo.png'; ?>" /> <?php _e( 'Integration Settings', 'haet_cleverreach' ); ?></h2>

	<form action="options.php" method="post">
		<?php settings_fields( 'haet_cleverreach_option_group' ); ?>
		<?php do_settings_sections('cleverreach_page_cleverreach-integrations'); ?>
		
		<?php submit_button(); ?>

	</form>
</div>
<div class="haet-cleverreach-sidebar">
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-signup.php'; ?>
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-ninja.php'; ?>
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-woocommerce.php'; ?>
    <?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-haet.php'; ?>
</div>