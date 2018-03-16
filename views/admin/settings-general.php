<div id="haet-cleverreach-settings-general" class="wrap haet-cleverreach-settings">

	<h2><img src="<?php echo HAET_CLEVERREACH_URL . 'images/logo.png'; ?>" /> <?php _e( 'General Settings', 'cleverreach' ); ?></h2>

	<form action="options.php" method="post">
		<?php if( isset( $_GET["cleverreach-athenticate"] ) && isset( $_GET["code"] ) ) : ?>
			<p class="message dashicons-before dashicons-lightbulb">
				<?php _e('Connection initialized. Please save your settings to test connection and load your lists.','cleverreach'); ?>
			</p>
		<?php elseif( $api_connected ): ?>
			<p class="success dashicons-before dashicons-yes">
				<?php echo $api_message; ?>
			</p>
		<?php elseif(isset($settings['api_key'])): ?>
			<p class="error dashicons-before dashicons-no-alt">
				<?php echo $api_message; ?>
			</p>
		<?php else: ?>
			<p class="message dashicons-before dashicons-lightbulb">
				<?php echo $api_message; ?>
			</p>
		<?php endif; ?>
		
		<?php settings_fields( 'haet_cleverreach_option_group' ); ?>
		<?php do_settings_sections('toplevel_page_cleverreach'); ?>

		<?php submit_button(); ?>

	</form>
	<?php if( $refresh_lists && !$list_result['success'] ): ?>
		<p class="error dashicons-before dashicons-no-alt">
			<?php echo $list_result['message']; ?>
		</p>
	<?php endif; ?>
	
	<?php if( !empty( $settings['lists'] ) && is_array( $settings['lists'] ) ): ?>
		<h3><?php _e('Your CleverReach lists','cleverreach'); ?></h3>
		<table class="haet_cleverreach_lists">
			<tbody>
				<tr>
					<th class="name">
						<?php _e('List Name','cleverreach'); ?>
					</th>
					<th class="count">
						<?php _e('# Subscribers','cleverreach'); ?>
					</th>
				</tr>
				<?php foreach ($settings['lists'] as $list): ?>
					<tr>
						<td class="name">
							<?php echo $list->name; ?>
						</td>
						<td class="count">
							<?php echo $list->count; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
	<?php if( isset($settings['api_key']) || isset($settings['token']) ): ?>
		<form method="post">
			<input type="hidden" name="haet_cleverreach_refresh" value="1" />
			<p>
				<input type="submit" value="<?php _e( 'Reload Lists from CleverReach', 'cleverreach' ); ?>" class="button" />
			</p>
		</form>
	<?php endif; ?>
</div>

<div class="haet-cleverreach-sidebar">
	<?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-signup.php'; ?>
	<?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-ninja.php'; ?>
	<?php require HAET_CLEVERREACH_PATH . 'views/admin/sidebar-haet.php'; ?>
</div>