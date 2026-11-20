<?php
/**
 * Homepage metadata for search and link previews.
 */
defined( 'ABSPATH' ) || exit;

const UMIMORI365_PAGE_TITLE = '海守365｜小型船舶の運航管理・一般不定期航路事業の登録支援';
const UMIMORI365_PAGE_DESCRIPTION = '株式会社NEXUSの海守365は、旅客定員1〜12名の小型船舶を対象に、一般不定期航路事業の登録手続き、安全運航管理、現地対応を支援します。';

function umimori365_has_homepage_metadata() {
	$use_theme_metadata = ! defined( 'WPSEO_VERSION' )
		&& ! defined( 'RANK_MATH_VERSION' )
		&& ! defined( 'AIOSEO_VERSION' )
		&& ! defined( 'SEOPRESS_VERSION' )
		&& ! defined( 'THE_SEO_FRAMEWORK_VERSION' );

	return is_front_page() && ! is_paged() && (int) get_query_var( 'page' ) <= 1
		&& apply_filters( 'umimori365_use_theme_metadata', $use_theme_metadata );
}

add_filter( 'document_title_parts', static function ( $parts ) {
	return umimori365_has_homepage_metadata() ? array( 'title' => UMIMORI365_PAGE_TITLE ) : $parts;
} );

add_action( 'wp_head', static function () {
	if ( ! umimori365_has_homepage_metadata() ) {
		return;
	}

	$home = home_url( '/' );
	$image = get_theme_file_uri( '/assets/social-preview.jpg' );
	$organization_id = $home . '#organization';
	$website_id = $home . '#website';
	$service_id = $home . '#service';
	$registration = get_theme_mod( 'umimori365_route_registration', UMIMORI365_ROUTE_REGISTRATION );

	// WordPress already supplies the canonical URL for a static front page.
	if ( ! is_singular() ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $home ) );
	}

	$meta_tags = array(
		'description'        => UMIMORI365_PAGE_DESCRIPTION,
		'twitter:card'       => 'summary_large_image',
		'twitter:image:alt'  => '沿岸を航行する小型船舶のイメージ',
	);
	foreach ( $meta_tags as $name => $content ) {
		printf( '<meta name="%s" content="%s">' . "\n", esc_attr( $name ), esc_attr( $content ) );
	}

	$open_graph = array(
		'og:type'         => 'website',
		'og:locale'       => 'ja_JP',
		'og:site_name'    => '海守365',
		'og:title'        => UMIMORI365_PAGE_TITLE,
		'og:description'  => UMIMORI365_PAGE_DESCRIPTION,
		'og:url'          => $home,
		'og:image'        => $image,
		'og:image:type'   => 'image/jpeg',
		'og:image:width'  => '1200',
		'og:image:height' => '675',
		'og:image:alt'    => $meta_tags['twitter:image:alt'],
	);
	foreach ( $open_graph as $property => $content ) {
		printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $property ), esc_attr( $content ) );
	}

	$organization = array(
		'@type' => 'Organization',
		'@id'   => $organization_id,
		'name'  => '株式会社NEXUS',
		'email' => 'info@c365.jp',
	);
	if ( '' !== $registration ) {
		$organization['identifier'] = array(
			'@type'      => 'PropertyValue',
			'propertyID' => '一般不定期航路事業者登録番号',
			'value'      => $registration,
		);
	}

	$graph = array(
		'@context' => 'https://schema.org',
		'@graph'   => array(
			$organization,
			array(
				'@type'      => 'WebSite',
				'@id'        => $website_id,
				'url'        => $home,
				'name'       => '海守365',
				'inLanguage' => 'ja',
				'publisher'  => array( '@id' => $organization_id ),
			),
			array(
				'@type'       => 'WebPage',
				'@id'         => $home . '#webpage',
				'url'         => $home,
				'name'        => UMIMORI365_PAGE_TITLE,
				'description' => UMIMORI365_PAGE_DESCRIPTION,
				'inLanguage'  => 'ja',
				'isPartOf'    => array( '@id' => $website_id ),
				'mainEntity'  => array( '@id' => $service_id ),
			),
			array(
				'@type'       => 'Service',
				'@id'         => $service_id,
				'url'         => $home . '#service',
				'name'        => '海守365',
				'serviceType' => '小型船舶の安全運航管理・一般不定期航路事業の登録支援',
				'description' => UMIMORI365_PAGE_DESCRIPTION,
				'provider'    => array( '@id' => $organization_id ),
			),
		),
	);
	$json = wp_json_encode( $graph, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	if ( false !== $json ) {
		echo '<script type="application/ld+json">' . $json . "</script>\n";
	}
} );
