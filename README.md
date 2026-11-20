# 海守365 WordPressサイト

小型船舶の安全運航管理サービス「海守365」の紹介・利用相談サイトです。公開先は https://umimori.c365.jp です。

## テーマのダウンロード

[WordPressテーマ ZIP](https://github.com/hiroshit924/umi365/raw/refs/heads/main/downloads/umimori365-theme-1.1.4.zip) をダウンロードして、WordPress管理画面の **外観 → テーマ → 新しいテーマを追加 → テーマのアップロード** からインストールできます。

## ローカルで起動

Docker Compose、curl、OpenSSL が必要です。

```sh
./scripts/start-local.sh
```

- サイトは http://127.0.0.1:8080 で確認できます。
- 管理画面は http://127.0.0.1:8080/wp-admin/ です。ユーザー名は `umimori-admin` で、パスワードは初回起動時にローカルの `.env` に生成されます。
- フォームで送ったテストメールは http://127.0.0.1:8025 の Mailpit に届きます。
- 停止には `docker compose stop` を使います。再起動時は同じスクリプトを実行してください。WordPress とデータベースの内容は Docker ボリュームに保存されます。

## 本番 WordPress への導入

1. 公開先のホスティングで WordPress、ドメインと TLS 証明書を設定します。WordPress アドレスとサイトアドレスを `https://umimori.c365.jp` に設定し、サイトの言語を日本語にします。
2. `wp-content/themes/umimori365` を本番環境の `wp-content/themes/` にコピーし、テーマ「海守365」を有効にします。テーマに同梱した元画像がロゴとして表示されます。管理画面で別のロゴを設定済みの場合は、**外観 → カスタマイズ → サイト基本情報 → ロゴ** からその設定を解除すると同梱ロゴが表示されます。ブラウザのタブには同じ画像をファビコンとして使います。管理画面の **設定 → 一般 → サイトアイコン** に画像が設定されている場合は、WordPressのサイトアイコンが優先されます。
3. WordPress公式ディレクトリの [Contact Form 7](https://ja.wordpress.org/plugins/contact-form-7/) をインストールし、有効にします。このリポジトリのローカル構成では WordPress 7.1 系と Contact Form 7 6.2.5 を使用します。
4. 本番 WordPress のルートで、下記のコマンドを実行します。`UMIMORI_MAIL_FROM` には本番メールサーバーが送信を許可するアドレスを指定してください。

   ```sh
   UMIMORI_MAIL_FROM=wordpress@umimori.c365.jp wp eval-file /path/to/scripts/provision-site.php
   ```

   問い合わせフォームがトップページに表示されます。送信時は、Contact Form 7 の **メール** が `info@c365.jp` に相談内容を送り、**メール (2)** が入力されたメールアドレスに受付メールを送ります。既存サイトの更新時も、このスクリプトを再実行してください。既存フォームの入力項目を維持したまま、通知の宛先と受付メールの設定を更新します。
5. ホスティングの送信機能または認証済み SMTP を設定し、本番フォームから試験送信して、`info@c365.jp` への通知と入力したアドレスへの受付メールの着信を確認します。ローカルのメール捕捉用ファイル `scripts/local-mail.php` は本番に配置しません。

## 事業番号の表示

「運営会社・事業登録」欄とフッターに「関東一不 第65号」を表示します。番号が変更された場合は、**外観 → カスタマイズ → 運営会社・事業番号** で更新できます。

## 公開後の検索設定

- 本番 WordPress の **設定 → 表示設定** で検索エンジンによるインデックスを許可し、`/robots.txt` と WordPress 標準の `/wp-sitemap.xml` を確認します。SEO プラグインがサイトマップを提供している場合は、その URL を使います。
- Google Search Console で公開先を確認し、サイトマップを登録してトップページの登録状況を確認します。
- テーマは、トップページにページタイトル、説明文、共有時のプレビュー、運営会社・サービスの構造化データを出力します。対応する SEO プラグインが有効な場合は、そのプラグインでこれらを設定します。他のプラグインとの重複を避けるには、`umimori365_use_theme_metadata` フィルターでテーマの出力を無効化できます。

## 制度の参照先

- [国土交通省 九州運輸局の一般不定期航路事業の制度資料（PDF）](https://wwwtb.mlit.go.jp/kyushu/content/000347783.pdf)
- [国土交通省 関東運輸局](https://wwwtb.mlit.go.jp/kanto/)
