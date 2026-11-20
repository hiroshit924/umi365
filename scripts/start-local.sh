#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")/.."

if [[ ! -e .env ]]; then
	( umask 077; set -o noclobber
	  printf 'WORDPRESS_DB_PASSWORD=%s\nWP_ADMIN_PASSWORD=%s\n' "$(openssl rand -hex 24)" "$(openssl rand -hex 24)" > .env )
fi

# Apache runs as www-data and must be able to read the source bind mounts.
chmod -R a+rX wp-content/themes/umimori365

docker compose up -d --wait
work_dir=$(mktemp -d)
trap 'rm -rf "$work_dir"' EXIT

curl --fail --silent --show-error --location --retry 2 --max-time 90 \
	https://github.com/wp-cli/wp-cli/releases/download/v2.12.0/wp-cli-2.12.0.phar \
	-o "$work_dir/wp-cli.phar"
docker compose cp "$work_dir/wp-cli.phar" wordpress:/tmp/umimori-wp-cli.phar

wp() {
	docker compose exec -T wordpress php /tmp/umimori-wp-cli.phar --allow-root "$@"
}

if ! wp core is-installed; then
	admin_pass=$(sed -n 's/^WP_ADMIN_PASSWORD=//p' .env)
	if [[ -z $admin_pass ]]; then
		echo 'Set WP_ADMIN_PASSWORD in .env.' >&2
		exit 1
	fi
	port=$(docker compose port wordpress 80)
	printf '%s\n' "$admin_pass" | docker compose exec -T wordpress sh -c '
		IFS= read -r admin_password
		exec php /tmp/umimori-wp-cli.phar --allow-root core install --url="$1" --title="海守365" \
			--admin_user=umimori-admin --admin_email=info@c365.jp --skip-email --admin_password="$admin_password"
	' sh "http://$port"
	wp option update timezone_string Asia/Tokyo
	wp option update blog_public 0
	wp rewrite structure '/%postname%/'
fi

wp theme activate umimori365
site_url=$(wp option get home)
curl --fail --silent --show-error --max-time 15 --output /dev/null "$site_url/"
printf '%s\n' "$site_url"
