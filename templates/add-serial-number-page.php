<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$row_action = empty( $_REQUEST['row_action'] ) ? '' : sanitize_key( $_REQUEST['row_action'] );
$type       = empty( $_REQUEST['type'] ) ? '' : sanitize_key( $_REQUEST['type'] );

if ( ! empty( $type ) && 'automate' == $type  ) {
	$title = __( 'Add New Serial Number', 'wc-serial-numbers' );
} else {

	if ( 'edit' == $row_action ) {
		$serial_number_id       = ! empty( $_REQUEST['serial_number'] ) ? intval( $_REQUEST['serial_number'] ) : '';
		$serial_number          = get_the_title( $serial_number_id );
		$product                = get_post_meta( $serial_number_id, 'product', true );
		$variation              = get_post_meta( $serial_number_id, 'variation', true );
		$deliver_times          = get_post_meta( $serial_number_id, 'deliver_times', true );
		$max_instance           = get_post_meta( $serial_number_id, 'max_instance', true );
		$validity_type          = get_post_meta( $serial_number_id, 'validity_type', true );
		$validity               = get_post_meta( $serial_number_id, 'validity', true );
		$image_license          = get_post_meta( $serial_number_id, 'image_license', true );
		$title                  = __( 'Edit Serial Number', 'wc-serial-numbers' );
		$submit                 = __( 'Save changes', 'wc-serial-numbers' );
		$action_type            = 'wsn_edit_serial_number';
		$input_serial_number_id = sprintf('<input type="hidden" name="serial_number_id" value="%d">', $serial_number_id);
	} else {
		$serial_number          = '';
		$product                = '';
		$variation              = '';
		$deliver_times          = '1';
		$max_instance           = '0';
		$validity_type          = 'days';
		$validity               = '';
		$image_license          = '';
		$title                  = __( 'Add New Serial Number', 'wc-serial-numbers' );
		$submit                 = __( 'Add Serial Number', 'wc-serial-numbers' );
		$action_type            = 'add_serial_number';
		$input_serial_number_id = '';
	}

}

?>


<div class="wrap wsn-container">

	<div class="ever-form-group">

		<h1 class="wp-heading-inline"><?php echo $title ?></h1>

		<a href="<?php echo add_query_arg( 'type', 'manual', WPWSN_ADD_SERIAL_PAGE ); ?>"
		   class="wsn-button-primary add-serial-title page-title-action"><?php _e( 'Add serial key manually', 'wc-serial-numbers' ) ?></a>

	</div>


	<div class="ever-panel">
		<?php

		if ( 'automate' == $type ) {

			ob_start();
			include WPWSN_TEMPLATES_DIR . '/generate-serial-number.php';
			$html = ob_get_clean();
			echo $html;

		} else {

			ob_start();
			include WPWSN_TEMPLATES_DIR . '/add-serial-number.php';
			$html = ob_get_clean();
			echo $html;

		}

		?>
	</div>

</div>


