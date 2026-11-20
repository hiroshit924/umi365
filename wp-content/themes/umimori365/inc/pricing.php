<?php
/**
 * Add the pricing page on activation or the first dashboard visit after an update.
 */
defined( 'ABSPATH' ) || exit;

add_action( 'after_switch_theme', 'umimori365_install_pricing_page' );
add_action( 'admin_init', static function () {
	if ( current_user_can( 'publish_pages' ) ) {
		umimori365_install_pricing_page();
	}
} );

function umimori365_install_pricing_page() {
	if ( get_option( 'umimori365_pricing_page_id' ) ) {
		return;
	}

	$page = get_page_by_path( 'plans', OBJECT, 'page' );
	$page_id = $page ? $page->ID : wp_insert_post( array(
		'post_type'     => 'page',
		'post_status'   => 'publish',
		'post_title'    => 'プラン・料金',
		'post_name'     => 'plans',
		'page_template' => 'pricing-page.php',
	), true );

	if ( $page_id && ! is_wp_error( $page_id ) ) {
		add_option( 'umimori365_pricing_page_id', $page_id );
	}
}

function umimori365_pricing_page_url() {
	$page_id = (int) get_option( 'umimori365_pricing_page_id' );
	return $page_id && 'publish' === get_post_status( $page_id ) ? get_permalink( $page_id ) : '';
}
