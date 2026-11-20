<?php $icon_dir = get_theme_file_uri( '/assets/operations-icons/' ); ?>
<figure class="service-diagram" id="operations-chart" aria-labelledby="diagram-title">
	<figcaption>
		<h3 id="diagram-title">海守365の運航支援の仕組み</h3>
		<p>株式会社NEXUSが一般不定期航路事業者として、定期用船契約に基づいて小型船舶の代理運航と安全管理を担います。</p>
	</figcaption>

	<div class="operations-map">
		<section class="operations-node operations-center" aria-labelledby="center-title">
			<h4 id="center-title">
				<span class="operations-icon"><img src="<?php echo esc_url( $icon_dir . 'center.png' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async"></span>
				<span>海守365 運航管理センター</span>
			</h4>
			<p>安全統括管理者・運航管理者が、24時間365日、海況・気象の確認や運航判断、現地支援、緊急時の対応を行います。</p>
		</section>

		<div class="operations-exchange" role="group" aria-label="事業者と海守365のやり取り">
			<div>
				<h4>事業者から海守365へ <span class="operations-direction operations-direction-up" aria-hidden="true"></span></h4>
				<ul>
					<li>定期用船契約（船舶・船員）</li>
					<li>運航前後・現地海況・緊急時の報告</li>
					<li>サービス利用料金の支払い</li>
				</ul>
			</div>
			<div>
				<h4>海守365から事業者へ <span class="operations-direction operations-direction-down" aria-hidden="true"></span></h4>
				<ul>
					<li>船舶点検・整備、備品の手配</li>
					<li>必要書類の提供、桟橋利用申請の代行</li>
					<li>運航可否の連絡、緊急時の指示</li>
					<li>用船料の支払い</li>
				</ul>
			</div>
		</div>

		<section class="operations-node operations-owner" aria-labelledby="owner-title">
			<h4 id="owner-title">
				<span class="operations-icon"><img src="<?php echo esc_url( $icon_dir . 'owner.png' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async"></span>
				<span>事業者・船舶オーナー</span>
			</h4>
			<p>船長・船員を手配し、船舶の運航と乗船客への対応を行います。船舶や旅客に関する保険にも加入します。</p>
		</section>

		<section class="operations-node operations-passengers" aria-labelledby="passengers-title">
			<h4 id="passengers-title">
				<span class="operations-icon"><img src="<?php echo esc_url( $icon_dir . 'passengers.png' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async"></span>
				<span>乗船されるお客様</span>
			</h4>
			<dl>
				<div><dt>お客様から海守365へ</dt><dd>乗船料のお支払い（オンライン決済）</dd></div>
				<div><dt>事業者からお客様へ</dt><dd>乗船案内・運航・船上での対応</dd></div>
			</dl>
		</section>

		<section class="operations-node operations-regulator" aria-labelledby="regulator-title">
			<h4 id="regulator-title">
				<span class="operations-icon"><img src="<?php echo esc_url( $icon_dir . 'regulator.png' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async"></span>
				<span>運輸局</span>
			</h4>
			<p>海守365が一般不定期航路事業の登録手続きや、定期・随時の運航事業報告を行います。</p>
		</section>

		<section class="operations-node operations-emergency" aria-labelledby="emergency-title">
			<h4 id="emergency-title">
				<span class="operations-icon"><img src="<?php echo esc_url( $icon_dir . 'emergency.png' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async"></span>
				<span><span class="operations-phrase">海上保安庁・</span><wbr><span class="operations-phrase">警察・消防・</span><wbr>自治体</span>
			</h4>
			<p>事故などの際は、事業者や海守365が必要な緊急連絡・報告を行い、関係機関が救難・救助に対応します。</p>
		</section>

		<section class="operations-node operations-advisor" aria-labelledby="advisor-title">
			<h4 id="advisor-title">
				<span class="operations-icon"><img src="<?php echo esc_url( $icon_dir . 'advisor.png' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async"></span>
				<span>顧問海事代理士</span>
			</h4>
			<p>法令改正や運輸局の通達を確認し、申請手続きや制度対応を専門的に支援します。</p>
		</section>

		<section class="operations-node operations-partners" aria-labelledby="partners-title">
			<h4 id="partners-title">
				<span class="operations-icon"><img src="<?php echo esc_url( $icon_dir . 'partners.png' ); ?>" alt="" width="24" height="24" loading="lazy" decoding="async"></span>
				<span>運航・保険のパートナー</span>
			</h4>
			<p>船長・船員の手配、船舶の整備、プレジャーボート保険や旅客傷害賠償責任保険の提案・手配を支援します。</p>
		</section>
	</div>
</figure>
