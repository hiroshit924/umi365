<section class="section company-registration" id="company" aria-labelledby="company-title">
	<div class="container">
		<h2 id="company-title">運営会社・事業登録</h2>
		<p class="company-intro">株式会社NEXUSは、一般不定期航路事業について関東運輸局の登録を受けています。</p>

		<div class="company-grid">
			<div class="company-card">
				<h3>事業登録情報</h3>
				<dl>
					<div><dt>事業の種類</dt><dd>内航一般不定期航路事業</dd></div>
					<?php $route_registration = get_theme_mod( 'umimori365_route_registration', UMIMORI365_ROUTE_REGISTRATION ); ?>
					<?php if ( '' !== $route_registration ) : ?>
						<div><dt>登録番号</dt><dd><?php echo esc_html( $route_registration ); ?></dd></div>
					<?php endif; ?>
					<div><dt>登録年月日</dt><dd><time datetime="2025-09-03">2025年（令和7年）9月3日</time></dd></div>
					<div><dt>旧制度での届出年度</dt><dd>2021年（令和3年）</dd></div>
				</dl>
			</div>

			<div class="company-card">
				<h3>会社情報</h3>
				<dl>
					<div><dt>販売事業者・運営会社</dt><dd>株式会社NEXUS</dd></div>
					<div><dt>運営責任者</dt><dd>平丸聖弥</dd></div>
					<div><dt>所在地</dt><dd>神奈川県横浜市中区福富町西通44-2 Speranza Sakuragicho 1F</dd></div>
					<div><dt>電話番号</dt><dd><a href="tel:+81452976375">045-297-6375</a></dd></div>
					<div><dt>メール</dt><dd><a href="mailto:info@c365.jp">info@c365.jp</a></dd></div>
				</dl>
			</div>
		</div>
	</div>
</section>
