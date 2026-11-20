(() => {
	const form = document.querySelector('#contact-form');
	if (!form) {
		return;
	}

	const endpoint = new URL(form.getAttribute('action'), window.location.href);
	if (endpoint.origin !== window.location.origin) {
		return;
	}

	const button = form.querySelector('button[type="submit"]');
	const status = form.querySelector('[role="status"]');
	let sending = false;

	form.addEventListener('submit', async (event) => {
		event.preventDefault();
		if (sending) {
			return;
		}

		const data = new FormData(form);
		data.set('umimori_async', '1');

		sending = true;
		button.disabled = true;
		status.textContent = '送信中です…';

		try {
			const response = await fetch(endpoint, { method: 'POST', body: data, redirect: 'error' });
			if (!response.ok) {
				throw new Error('Unexpected response');
			}

			const { result, message } = await response.json();
			if (typeof message !== 'string' || !message) {
				throw new Error('Missing confirmation status');
			}

			if (result === 'sent' || result === 'received') {
				form.reset();
			}
			status.textContent = message;
		} catch {
			status.textContent = '送信結果を確認できませんでした。受付メールが届いているかご確認ください。';
		} finally {
			button.disabled = false;
			sending = false;
		}
	});
})();
