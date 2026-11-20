<?php
/**
 * Validate and deliver public consultation submissions.
 */
defined( 'ABSPATH' ) || exit;

add_action( 'admin_post_umimori365_contact', 'umimori365_submit_contact' );
add_action( 'admin_post_nopriv_umimori365_contact', 'umimori365_submit_contact' );

function umimori365_contact_result( $result ) {
	wp_safe_redirect( add_query_arg( 'consultation', $result, home_url( '/' ) ) . '#contact-form', 303 );
	exit;
}

function umimori365_submit_contact() {
	$fields = array();
	foreach ( array( 'umimori_nonce', 'website', 'name', 'company', 'email', 'message' ) as $name ) {
		$fields[ $name ] = isset( $_POST[ $name ] ) && is_string( $_POST[ $name ] ) ? trim( wp_unslash( $_POST[ $name ] ) ) : '';
	}

	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || ! wp_verify_nonce( $fields['umimori_nonce'], 'umimori365_contact' ) ) {
		umimori365_contact_result( 'invalid' );
	}
	if ( '' !== $fields['website'] ) {
		umimori365_contact_result( 'invalid' );
	}

	$name = sanitize_text_field( $fields['name'] );
	$company = sanitize_text_field( $fields['company'] );
	$email = $fields['email'];
	$message = sanitize_textarea_field( $fields['message'] );
	if ( '' === $name || '' === $message || ! is_email( $email ) || strlen( $email ) > 254
		|| mb_strlen( $name ) > 100 || mb_strlen( $company ) > 150 || mb_strlen( $message ) > 3000 ) {
		umimori365_contact_result( 'invalid' );
	}

	$ip = $_SERVER['REMOTE_ADDR'] ?? '';
	$ip_limit = 'umimori_ip_' . wp_hash( $ip );
	$email_limit = 'umimori_email_' . wp_hash( strtolower( $email ) );
	$attempts = (int) get_transient( $ip_limit );
	if ( $attempts >= 8 || get_transient( $email_limit ) ) {
		umimori365_contact_result( 'limit' );
	}
	set_transient( $ip_limit, $attempts + 1, 10 * MINUTE_IN_SECONDS );

	$settings = umimori365_m365_settings();
	$token = umimori365_m365_token( $settings );
	$recipient = 'info@c365.jp';
	if ( ! $token || ! umimori365_m365_send(
		$settings['sender'], $token, $recipient, $email, '【海守365】無料相談・お問い合わせ',
		"お名前: $name\n会社名・屋号: $company\nメールアドレス: $email\n\nご相談内容\n$message"
	) ) {
		umimori365_contact_result( 'failed' );
	}
	set_transient( $email_limit, 1, MINUTE_IN_SECONDS );

	$confirmed = umimori365_m365_send(
		$settings['sender'], $token, $email, $recipient, '【海守365】お問い合わせを受け付けました',
		"海守365へお問い合わせいただき、ありがとうございます。\n\n内容を確認のうえ、担当者からご連絡いたします。\n\n株式会社NEXUS 海守365運航管理センター"
	);
	umimori365_contact_result( $confirmed ? 'sent' : 'received' );
}
