<?php
$messages = array(
	'sent'     => 'お問い合わせを受け付けました。入力されたアドレスに受付メールをお送りしました。',
	'received' => 'お問い合わせを受け付けました。受付メールの送信は完了しませんでした。担当者からのご連絡をお待ちください。',
	'failed'   => '送信できませんでした。時間をおいて再度お試しください。',
	'invalid'  => '入力内容をご確認のうえ、もう一度送信してください。',
	'limit'    => '続けて送信されました。時間をおいて再度お試しください。',
);
$result = isset( $_GET['consultation'] ) && is_string( $_GET['consultation'] ) ? sanitize_key( wp_unslash( $_GET['consultation'] ) ) : '';
?>
<form id="contact-form" class="form-grid" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="umimori365_contact">
	<?php wp_nonce_field( 'umimori365_contact', 'umimori_nonce', false ); ?>
	<div hidden><label>ウェブサイト <input name="website" type="text" tabindex="-1" autocomplete="off"></label></div>
	<?php if ( isset( $messages[ $result ] ) ) : ?>
		<p class="form-wide contact-status" role="status"><?php echo esc_html( $messages[ $result ] ); ?></p>
	<?php endif; ?>
	<p><label>お名前<span class="required">必須</span><br><input name="name" type="text" autocomplete="name" maxlength="100" required></label></p>
	<p><label>会社名・屋号<br><input name="company" type="text" autocomplete="organization" maxlength="150"></label></p>
	<p class="form-wide"><label>メールアドレス<span class="required">必須</span><br><input name="email" type="email" autocomplete="email" maxlength="254" required></label></p>
	<p class="form-wide"><label>ご相談内容<span class="required">必須</span><br><textarea name="message" maxlength="3000" placeholder="運航予定のエリア、船舶、相談したいことなど" required></textarea></label></p>
	<p class="form-wide"><button class="button button-primary" type="submit">お問い合わせを送信</button></p>
</form>
