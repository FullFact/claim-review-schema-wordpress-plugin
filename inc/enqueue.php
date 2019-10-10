<?php

/**
 * Enqueue the admin styles for the plugin
 *
 * @return void
 */
function claim_review_admin_style() {

	$plugin_data = get_plugin_data( __FILE__ );
	wp_register_style( 'claim_review_admin_css', CLAIMREVIEW_PLUGIN_URL . '/css/admin-styles.css', false, $plugin_data['Version'] );
	wp_enqueue_style( 'claim_review_admin_css' );
	wp_register_script( 'claim_review_admin_js', CLAIMREVIEW_PLUGIN_URL . '/js/claim-review-admin.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-datepicker' ), $plugin_data['Version'] );
	wp_register_style( 'claim_review_jquery_css', CLAIMREVIEW_PLUGIN_URL . '/css/jquery-styles.css', false, $plugin_data['Version'] );
	wp_enqueue_style( 'claim_review_jquery_css' );

	$metabox = array(
		'metabox' => claim_review_build_claim_box( '%%JS%%' ),
	);

	wp_localize_script( 'claim_review_admin_js', 'metabox', $metabox );

	wp_enqueue_script( 'claim_review_admin_js' );
}
add_action( 'admin_enqueue_scripts', 'claim_review_admin_style' );