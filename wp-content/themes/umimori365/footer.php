<footer class="site-footer">
	<div class="container footer-inner">
		<div>
			<a class="footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">海守365</a>
			<p>小型船舶 安全運航管理サービス</p>
			<dl class="operator-details">
				<div><dt>運営会社</dt><dd><a href="<?php echo esc_url( home_url( '/#company' ) ); ?>">株式会社NEXUS</a></dd></div>
				<?php $route_registration = get_theme_mod( 'umimori365_route_registration', UMIMORI365_ROUTE_REGISTRATION ); ?>
				<?php if ( '' !== $route_registration ) : ?>
					<div><dt>一般不定期航路事業者登録番号</dt><dd><?php echo esc_html( $route_registration ); ?></dd></div>
				<?php endif; ?>
			</dl>
		</div>
		<div class="footer-right">
			<a href="<?php echo esc_url( home_url( '/#privacy' ) ); ?>">個人情報の取り扱い</a>
			<small>© <?php echo esc_html( wp_date( 'Y' ) ); ?> 海守365</small>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
