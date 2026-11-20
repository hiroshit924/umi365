<?php
/**
 * Theme setup and assets.
 */
defined( 'ABSPATH' ) || exit;

const UMIMORI365_ROUTE_REGISTRATION = '関東一不 第65号';

require_once __DIR__ . '/inc/seo.php';
require_once __DIR__ . '/inc/microsoft365.php';
require_once __DIR__ . '/inc/contact.php';
require_once __DIR__ . '/inc/pricing.php';

add_action( 'after_setup_theme', static function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'custom-logo', array( 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'html5', array( 'style', 'script', 'gallery', 'caption' ) );
} );

add_action( 'wp_enqueue_scripts', static function () {
	wp_enqueue_style( 'umimori365', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
	if ( is_front_page() ) {
		wp_enqueue_script( 'umimori365-contact', get_theme_file_uri( '/assets/contact-form.js' ), array(), wp_get_theme()->get( 'Version' ), true );
	}
} );

add_action( 'wp_head', static function () {
	if ( ! has_site_icon() && is_readable( get_theme_file_path( '/assets/logo.png' ) ) ) {
		printf(
			'<link rel="icon" type="image/png" sizes="1254x1254" href="%s">' . "\n",
			esc_url( get_theme_file_uri( '/assets/logo.png' ) )
		);
	}
} );

function umimori365_brand() {
	if ( has_custom_logo() ) {
		echo get_custom_logo();
		return;
	}

	$logo = get_theme_file_path( '/assets/logo.png' );
	?>
	<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<?php if ( is_readable( $logo ) ) : ?>
			<img src="<?php echo esc_url( get_theme_file_uri( '/assets/logo.png' ) ); ?>" alt="海守365" width="1254" height="1254">
		<?php else : ?>
			<span class="brand-name">海守<span>365</span></span>
			<span class="brand-description">小型船舶 安全運航管理サービス</span>
		<?php endif; ?>
	</a>
	<?php
}

add_action( 'customize_register', static function ( WP_Customize_Manager $customizer ) {
	$customizer->add_section( 'umimori365_operator', array( 'title' => '運営会社・事業番号' ) );
	$customizer->add_setting(
		'umimori365_route_registration',
		array(
			'default'           => UMIMORI365_ROUTE_REGISTRATION,
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$customizer->add_control(
		'umimori365_route_registration',
		array(
			'label'       => '一般不定期航路事業者登録番号',
			'description' => '登録通知書に記載されている番号を入力してください。',
			'section'     => 'umimori365_operator',
			'type'        => 'text',
		)
	);
} );
