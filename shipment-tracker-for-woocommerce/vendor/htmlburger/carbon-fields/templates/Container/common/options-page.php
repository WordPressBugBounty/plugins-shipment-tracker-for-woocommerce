<?php
$container_id = $this->get_id();
if ( ! isset( $container_css_class ) ) {
	$container_css_class = 'generic-container';
}
?>
<div class="wrap carbon-<?php echo $container_css_class; ?>">
	<h2><?php echo $this->title ?></h2>

	<?php if ( $this->errors ) :  ?>
		<div class="error settings-error">
			<?php foreach ( $this->errors as $error ) :  ?>
				<p><strong><?php echo $error; ?></strong></p>
			<?php endforeach ?>
		</div>
	<?php elseif ( $this->notifications ) :  ?>
		<?php foreach ( $this->notifications as $notification ) :  ?>
			<div class="settings-error updated">
				<p><strong><?php echo $notification ?></strong></p>
			</div>
		<?php endforeach ?>
	<?php endif; ?>

	<form method="post" id="<?php echo $container_css_class; ?>-form" enctype="multipart/form-data" action="">
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2">
				<div id="post-body-content">

					<?php do_action( "{$container_id}_before_fields" ); ?>

					<div class="postbox carbon-box" id="<?php echo $this->get_id(); ?>">
						<fieldset class="inside <?php echo $container_css_class; ?>-container container-<?php echo $this->get_id(); ?>"></fieldset>
					</div>

					<?php do_action( "{$container_id}_after_fields" ); ?>
				</div>

				<div id="postbox-container-1" class="postbox-container">

					<?php do_action( "{$container_id}_before_sidebar" ); ?>

					<div id="submitdiv" class="postbox">
						<h3><?php _e( 'Actions', 'carbon-fields' ); ?></h3>

						<div id="major-publishing-actions">

							<div id="publishing-action">
								<span class="spinner"></span>

								<?php
									$filter_name  = 'carbon_fields_' . str_ireplace( '-', '_', sanitize_title( $this->title ) ) . '_button_label';
									$button_label = apply_filters( $filter_name, __( 'Save Changes', 'carbon-fields' ) );
								?>

								<input type="submit" value="<?php echo esc_attr( $button_label ); ?>" name="publish" id="publish" class="button button-primary button-large">
							</div>

							<div class="clear"></div>
						</div>
					</div>

					<?php do_action( "{$container_id}_after_sidebar" ); ?>

				</div>
			</div>
		</div>
	</form>
</div>
