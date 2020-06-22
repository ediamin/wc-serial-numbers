<?php

namespace PluginEver\SerialNumbers\Admin;

use PluginEver\SerialNumbers\Admin\Table\Activations_Table;

defined( 'ABSPATH' ) || exit();

class Activations_Page {

	/**
	 * Render serial number page.
	 *
	 * @since 1.2.0
	 */
	public static function output() {
		require_once dirname( __FILE__ ) . '/tables/class-table-activations.php';
		wp_enqueue_style( 'serial-list-tables' );
		$list_table = new Activations_Table();
		$action     = $list_table->current_action();
		self::handle_actions( $action );
		$list_table->prepare_items();
		?>
		<div class="wrap">
			<h1 class="wp-heading-inline"><?php _e( 'Activations', 'wc-serial-numbers' ); ?></h1>
			<hr class="wp-header-end">
			<form method="get">
				<div class="serials-table">
					<?php $list_table->search_box( __( 'Search', 'wc-serial-numbers' ), 'serial-number' ); ?>
					<input type="hidden" name="page" value="serial-numbers"/>
					<?php $list_table->views() ?>
					<?php $list_table->display() ?>
				</div>
			</form>
		</div>
		<?php
	}

	/**
	 * Handle table actions.
	 *
	 * @since 1.2.0
	 */
	protected static function handle_actions( $doaction ) {
		if ( $doaction ) {
			if ( isset( $_REQUEST['id'] ) ) {
				$ids      = [ intval( $_REQUEST['id'] ) ];
				$doaction = ( - 1 != $_REQUEST['action'] ) ? $_REQUEST['action'] : $_REQUEST['action2'];
			} elseif ( isset( $_REQUEST['ids'] ) ) {
				$ids = array_map( 'absint', $_REQUEST['ids'] );
			} elseif ( wp_get_referer() ) {
				wp_safe_redirect( wp_get_referer() );
				exit;
			}
			foreach ( $ids as $id ) { // Check the permissions on each.
				switch ( $doaction ) {
					case 'delete':
						wc_serial_numbers_delete_serial( $id );
						break;
					case 'activate':
						wc_serial_numbers_insert_item( array(
							'id'         => $id,
							'order_id'   => null,
							'order_date' => null,
							'status'     => 'available',
						) );
						break;
					case 'deactivate':
						wc_serial_numbers_insert_item( array(
							'id'     => $id,
							'status' => 'inactive',
						) );
						break;
				}
			}

			wp_safe_redirect( wp_get_referer() );
			exit;
		} elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
			wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			exit;
		}
	}
}