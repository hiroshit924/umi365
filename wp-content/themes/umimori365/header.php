<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">本文へ移動</a>
<header class="site-header">
	<div class="container header-inner">
		<?php umimori365_brand(); ?>
		<nav class="site-nav" aria-label="メインメニュー">
			<a href="<?php echo esc_url( home_url( '/#service' ) ); ?>">サービス</a>
			<a href="<?php echo esc_url( home_url( '/#background' ) ); ?>">事業の背景</a>
			<a href="<?php echo esc_url( home_url( '/#flow' ) ); ?>">ご利用の流れ</a>
			<?php $pricing_url = umimori365_pricing_page_url(); ?>
			<?php if ( $pricing_url ) : ?>
				<a href="<?php echo esc_url( $pricing_url ); ?>"<?php if ( is_page( (int) get_option( 'umimori365_pricing_page_id' ) ) ) : ?> aria-current="page"<?php endif; ?>>プラン・料金</a>
			<?php endif; ?>
		</nav>
		<a class="button button-primary header-cta" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>">無料相談 <span aria-hidden="true">↗</span></a>
	</div>
</header>
