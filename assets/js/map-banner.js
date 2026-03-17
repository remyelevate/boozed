/**
 * Map banner section: init OpenStreetMap (Leaflet) for each .map-banner__map
 */
(function () {
	function escapeHtml(text) {
		var div = document.createElement('div');
		div.textContent = text;
		return div.innerHTML;
	}

	function init() {
		if (typeof L === 'undefined') return;

		document.querySelectorAll('.map-banner__map').forEach(function (el) {
			if (el.dataset.initialized === '1') return;
			el.dataset.initialized = '1';

			var address = el.dataset.address || '';
			var zoom = parseInt(el.dataset.zoom, 10) || 16;
			var defaultLat = parseFloat(el.dataset.defaultLat) || 51.9966;
			var defaultLng = parseFloat(el.dataset.defaultLng) || 4.3644;

			var map = L.map(el, { scrollWheelZoom: false }).setView([defaultLat, defaultLng], zoom);

			L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
			}).addTo(map);

			var marker = L.marker([defaultLat, defaultLng]).addTo(map);
			if (address) {
				marker.bindPopup('<strong>' + escapeHtml(address) + '</strong>').openPopup();
			}

			if (address && address.trim()) {
				fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(address.trim()) + '&limit=1', {
					headers: { 'Accept': 'application/json' }
				})
					.then(function (r) { return r.json(); })
					.then(function (results) {
						if (results && results[0]) {
							var lat = parseFloat(results[0].lat);
							var lng = parseFloat(results[0].lon);
							map.setView([lat, lng], zoom);
							marker.setLatLng([lat, lng]);
							marker.setPopupContent('<strong>' + escapeHtml(address) + '</strong>').openPopup();
						}
					})
					.catch(function () {});
			}
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
