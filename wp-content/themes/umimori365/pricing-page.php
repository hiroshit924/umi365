<?php
/**
 * Template Name: 海守365 プラン・料金
 */
get_header();

$plans = array(
	array( 'name' => 'ミニプラン', 'fleet' => '1艇', 'routes' => '2航路まで', 'icon' => 'owner', 'monthly' => '3', 'voyages' => '10航海まで', 'initial' => '20' ),
	array( 'name' => 'スタンダードプラン', 'fleet' => '2〜3艇', 'routes' => '5航路まで', 'icon' => 'passengers', 'monthly' => '5', 'voyages' => '20航海まで', 'initial' => '30' ),
	array( 'name' => 'カスタムプラン', 'fleet' => '4艇以上', 'routes' => '', 'icon' => 'partners', 'monthly' => '', 'voyages' => '', 'initial' => '' ),
);
$contact_url = home_url( '/#contact' );
?>
<main id="main" class="pricing">
	<section class="pricing-hero" aria-labelledby="pricing-title">
		<div class="container">
			<a class="pricing-home" href="<?php echo esc_url( home_url( '/' ) ); ?>">← 海守365トップへ</a>
			<h1 id="pricing-title">プラン・料金</h1>
			<p>運航する艇数と航路数に合わせて選べる、<br class="desktop-break">海守365の小型船舶運航サポート。</p>
		</div>
	</section>
	<section class="pricing-options" aria-labelledby="pricing-options-title">
		<div class="container">
			<div class="pricing-intro">
				<h2 id="pricing-options-title">規模に合わせて選べる3つのプラン</h2>
				<p>以下は料金の目安です。支援内容と正式なお見積もりは、無料相談でご案内します。</p>
			</div>
			<div class="pricing-grid">
				<?php foreach ( $plans as $plan ) : ?>
					<article class="pricing-card">
						<div class="pricing-card-icon"><img src="<?php echo esc_url( get_theme_file_uri( '/assets/operations-icons/' . $plan['icon'] . '.png' ) ); ?>" alt="" width="30" height="30"></div>
						<h3><?php echo esc_html( $plan['name'] ); ?></h3>
						<p class="pricing-scope">小型船舶 <?php echo esc_html( $plan['fleet'] ); ?><?php if ( $plan['routes'] ) : ?>・<?php echo esc_html( $plan['routes'] ); ?><?php endif; ?></p>
						<?php if ( $plan['monthly'] ) : ?>
							<p class="pricing-amount"><span>月額</span> <strong><?php echo esc_html( $plan['monthly'] ); ?></strong> 万円</p>
							<p class="pricing-voyages"><?php echo esc_html( $plan['voyages'] ); ?></p>
							<dl class="pricing-details">
								<div><dt>初期費用</dt><dd><?php echo esc_html( $plan['initial'] ); ?>万円</dd></div>
								<div><dt>航海の追加</dt><dd>5航海ごとに2万円</dd></div>
								<div><dt>追加航路の設定</dt><dd>1航路あたり3万円</dd></div>
							</dl>
						<?php else : ?>
							<p class="pricing-quote">個別見積もり</p>
							<p class="pricing-custom-description">運航する艇数や航路、必要なサポートを伺い、お見積もりをご案内します。</p>
						<?php endif; ?>
						<a class="button pricing-card-cta" href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html( $plan['name'] ); ?>を相談 <span aria-hidden="true">↗</span></a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
</main>
<?php get_footer(); ?>
