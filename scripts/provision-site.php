<?php
/**
 * Run with wp eval-file scripts/provision-site.php from an installed WordPress instance.
 */
if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
	exit;
}

if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
	WP_CLI::error( 'Contact Form 7 を有効化してください。' );
}

$form_id = (int) get_option( 'umimori365_contact_form_id' );
$contact_form = $form_id ? wpcf7_contact_form( $form_id ) : null;
if ( ! $contact_form ) {
	$contact_form = WPCF7_ContactForm::get_template(
		array( 'title' => '海守365 利用相談・お問い合わせ', 'locale' => 'ja' )
	);
	$properties = $contact_form->get_properties();
	$properties['form'] = <<<'FORM'
<div class="form-grid">
<p><label>お名前<span class="required">必須</span><br>[text* your-name autocomplete:name maxlength:100]</label></p>
<p><label>会社名・屋号<br>[text your-company autocomplete:organization maxlength:150]</label></p>
<p class="form-wide"><label>メールアドレス<span class="required">必須</span><br>[email* your-email autocomplete:email maxlength:254]</label></p>
<p class="form-wide"><label>ご相談内容<span class="required">必須</span><br>[textarea* your-message maxlength:3000 placeholder "運航予定のエリア、船舶、相談したいことなど"]</label></p>
<p class="form-wide">[submit class:button class:button-primary "お問い合わせを送信"]</p>
</div>
FORM;
	$from_address = getenv( 'UMIMORI_MAIL_FROM' ) ?: 'wordpress@umimori.c365.jp';
	$site_host = wp_parse_url( home_url(), PHP_URL_HOST );

	if ( ! is_email( $from_address ) ) {
		WP_CLI::error( 'UMIMORI_MAIL_FROM に有効な送信元メールアドレスを指定してください。' );
	}
	if ( 'local' !== wp_get_environment_type() && 'umimori.c365.jp' !== strtolower( (string) $site_host ) ) {
		WP_CLI::warning( 'サイトURLが公開予定の https://umimori.c365.jp と異なります。' );
	}
	$properties['mail'] = array_merge(
		$properties['mail'],
		array(
			'recipient' => 'info@c365.jp',
			'sender' => '海守365 <' . $from_address . '>',
			'subject' => '【海守365】利用相談・お問い合わせ',
			'additional_headers' => 'Reply-To: [your-email]',
			'body' => "お名前: [your-name]\n会社名・屋号: [your-company]\nメールアドレス: [your-email]\n\nご相談内容\n[your-message]",
			'use_html' => false,
		)
	);
	$properties['messages'] = array_merge(
		$properties['messages'],
		array(
			'mail_sent_ok' => 'お問い合わせを受け付けました。',
			'validation_error' => '入力内容をご確認ください。',
			'invalid_required' => 'この項目を入力してください。',
			'invalid_too_long' => '文字数が上限を超えています。',
			'invalid_email' => '有効なメールアドレスを入力してください。',
			'spam' => '送信できませんでした。時間をおいて再度お試しください。',
		)
	);
} else {
	$properties = $contact_form->get_properties();
}

$email_fields = WPCF7_FormTagsManager::get_instance()->filter(
	$properties['form'],
	array( 'name' => 'your-email', 'type' => 'email*' )
);
if ( ! $email_fields ) {
	WP_CLI::error( 'フォームに必須メールアドレス欄 [email* your-email] を設定してください。' );
}

$properties['mail']['recipient'] = 'info@c365.jp';
$properties['messages']['mail_sent_ng'] = '送信できませんでした。時間をおいて再度フォームからお試しください。';
$properties['mail_2'] = array(
	'active'             => true,
	'recipient'          => '[your-email]',
	'sender'             => $properties['mail']['sender'],
	'subject'            => '【海守365】お問い合わせを受け付けました',
	'additional_headers' => 'Reply-To: info@c365.jp',
	'body'               => "海守365へお問い合わせいただき、ありがとうございます。\n\n無料相談のお問い合わせを受け付けました。内容を確認のうえ、担当者からご連絡いたします。\n\n株式会社NEXUS 海守365運航管理センター",
	'attachments'        => '',
	'use_html'           => false,
	'exclude_blank'      => false,
);

$contact_form->set_properties( $properties );
$form_id = $contact_form->save();
if ( ! $form_id ) {
	WP_CLI::error( '問い合わせフォームを保存できませんでした。' );
}
update_option( 'umimori365_contact_form_id', $form_id );

WP_CLI::success( '海守365の問い合わせフォーム ID: ' . $form_id );
