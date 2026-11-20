<?php
/**
 * Route the local Compose site's mail to Mailpit.
 */
defined( 'ABSPATH' ) || exit;

if ( 'local' === wp_get_environment_type() ) {
	add_action( 'phpmailer_init', static function ( $mailer ) {
		$mailer->isSMTP();
		$mailer->Host = 'mailpit';
		$mailer->Port = 1025;
		$mailer->SMTPAuth = false;
		$mailer->SMTPAutoTLS = false;
	} );
}
