<?php
$result = isset( $_GET['consultation'] ) && is_string( $_GET['consultation'] ) ? sanitize_key( wp_unslash( $_GET['consultation'] ) ) : '';
?>
<form id="contact-form" class="form-grid" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="umimori365_contact">
	<?php wp_nonce_field( 'umimori365_contact', 'umimori_nonce', false ); ?>
	<div hidden><label>ウェブサイト <input name="website" type="text" tabindex="-1" autocomplete="off"></label></div>
	<p><label>お名前<span class="required">必須</span><br><input name="name" type="text" autocomplete="name" maxlength="100" required></label></p>
	<p><label>会社名・屋号<br><input name="company" type="text" autocomplete="organization" maxlength="150"></label></p>
	<p class="form-wide"><label>メールアドレス<span class="required">必須</span><br><input name="email" type="email" autocomplete="email" maxlength="254" required></label></p>
	<p class="form-wide"><label>ご相談内容<span class="required">必須</span><br><textarea name="message" maxlength="3000" placeholder="運航予定のエリア、船舶、相談したいことなど" required></textarea></label></p>
	<p class="form-wide"><button class="button button-primary" type="submit">お問い合わせを送信</button></p>
	<p class="form-wide contact-status" role="status"><?php echo esc_html( umimori365_contact_message( $result ) ); ?></p>
</form>
