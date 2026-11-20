<?php
/**
 * Microsoft 365 settings and Graph mail delivery.
 */
defined( 'ABSPATH' ) || exit;

function umimori365_m365_settings() {
	return wp_parse_args( get_option( 'umimori365_m365', array() ), array(
		'tenant' => '', 'client' => '', 'sender' => '', 'secret' => '',
	) );
}

function umimori365_m365_key() {
	return hash( 'sha256', wp_salt( 'auth' ) . '|umimori365-m365', true );
}

add_action( 'admin_menu', static function () {
	add_options_page( '海守365 Microsoft 365', '海守365 Microsoft 365', 'manage_options', 'umimori365-m365', 'umimori365_m365_admin' );
} );

function umimori365_m365_admin() {
	$settings = umimori365_m365_settings();
	?>
	<div class="wrap">
		<h1>海守365 Microsoft 365</h1>
		<?php settings_errors( 'umimori365_m365' ); ?>
		<p>問い合わせフォームは、下記のメールボックスから運営側への通知と相談者への受付メールを送信します。</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
			<?php settings_fields( 'umimori365_m365' ); ?>
			<table class="form-table" role="presentation">
				<?php foreach ( array( 'tenant' => 'テナント ID', 'client' => 'アプリケーション ID', 'sender' => '送信元メールボックス' ) as $field => $label ) : ?>
					<tr>
						<th scope="row"><label for="umimori-<?php echo esc_attr( $field ); ?>"><?php echo esc_html( $label ); ?></label></th>
						<td><input id="umimori-<?php echo esc_attr( $field ); ?>" name="umimori365_m365[<?php echo esc_attr( $field ); ?>]" value="<?php echo esc_attr( $settings[ $field ] ); ?>" class="regular-text" required></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<th scope="row"><label for="umimori-secret">クライアント シークレットの値</label></th>
					<td>
						<input id="umimori-secret" name="umimori365_m365[secret]" type="password" class="regular-text" autocomplete="new-password">
						<p class="description"><?php echo $settings['secret'] ? '登録済みです。更新する場合だけ入力してください。' : 'Microsoft Entraで発行したシークレットの値を入力してください。'; ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

add_action( 'admin_init', static function () {
	add_option( 'umimori365_m365', array(), '', false );
	register_setting( 'umimori365_m365', 'umimori365_m365', array( 'sanitize_callback' => 'umimori365_m365_sanitize' ) );
} );

function umimori365_m365_sanitize( $input ) {
	$old = umimori365_m365_settings();
	$new = array();
	foreach ( array( 'tenant', 'client', 'sender', 'secret' ) as $field ) {
		$new[ $field ] = isset( $input[ $field ] ) && is_string( $input[ $field ] ) ? trim( $input[ $field ] ) : '';
	}

	$uuid = '/^[a-f0-9]{8}(?:-[a-f0-9]{4}){3}-[a-f0-9]{12}$/iD';
	if ( ! preg_match( $uuid, $new['tenant'] ) || ! preg_match( $uuid, $new['client'] ) || ! is_email( $new['sender'] ) ) {
		add_settings_error( 'umimori365_m365', 'invalid', 'テナントID、アプリケーションID、送信元メールアドレスをご確認ください。' );
		return $old;
	}
	if ( ! function_exists( 'sodium_crypto_secretbox' ) ) {
		add_settings_error( 'umimori365_m365', 'sodium', 'サーバーのPHPで Sodium 拡張を有効にしてください。' );
		return $old;
	}
	if ( '' !== $new['secret'] ) {
		$nonce = random_bytes( SODIUM_CRYPTO_SECRETBOX_NONCEBYTES );
		$new['secret'] = base64_encode( $nonce . sodium_crypto_secretbox( $new['secret'], $nonce, umimori365_m365_key() ) );
	} elseif ( ! $old['secret'] || $old['tenant'] !== $new['tenant'] || $old['client'] !== $new['client'] ) {
		add_settings_error( 'umimori365_m365', 'secret', '接続先のアプリを設定する際は、シークレットの値も入力してください。' );
		return $old;
	} else {
		$new['secret'] = $old['secret'];
	}

	return $new;
}

function umimori365_m365_token( $settings ) {
	$sealed = base64_decode( $settings['secret'], true );
	if ( ! function_exists( 'sodium_crypto_secretbox_open' ) || ! $sealed
		|| strlen( $sealed ) < SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES
		|| ! $settings['tenant'] || ! $settings['client'] || ! is_email( $settings['sender'] ) ) {
		return '';
	}
	$length = SODIUM_CRYPTO_SECRETBOX_NONCEBYTES;
	$secret = sodium_crypto_secretbox_open( substr( $sealed, $length ), substr( $sealed, 0, $length ), umimori365_m365_key() );
	if ( false === $secret ) {
		return '';
	}

	$response = wp_remote_post( 'https://login.microsoftonline.com/' . rawurlencode( $settings['tenant'] ) . '/oauth2/v2.0/token', array(
		'timeout'     => 10,
		'redirection' => 0,
		'body'        => array(
			'client_id'     => $settings['client'],
			'client_secret' => $secret,
			'scope'         => 'https://graph.microsoft.com/.default',
			'grant_type'    => 'client_credentials',
		),
	) );
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return '';
	}
	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	return isset( $data['access_token'] ) && is_string( $data['access_token'] ) ? $data['access_token'] : '';
}

function umimori365_m365_send( $sender, $token, $to, $reply_to, $subject, $body ) {
	$response = wp_remote_post( 'https://graph.microsoft.com/v1.0/users/' . rawurlencode( $sender ) . '/sendMail', array(
		'timeout'     => 10,
		'redirection' => 0,
		'headers'     => array( 'Authorization' => 'Bearer ' . $token, 'Content-Type' => 'application/json' ),
		'body'        => wp_json_encode( array(
			'message' => array(
				'subject'      => $subject,
				'body'         => array( 'contentType' => 'Text', 'content' => $body ),
				'toRecipients' => array( array( 'emailAddress' => array( 'address' => $to ) ) ),
				'replyTo'      => array( array( 'emailAddress' => array( 'address' => $reply_to ) ) ),
			),
		) ),
	) );

	return ! is_wp_error( $response ) && 202 === wp_remote_retrieve_response_code( $response );
}
