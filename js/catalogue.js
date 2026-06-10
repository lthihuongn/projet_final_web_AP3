document.addEventListener('DOMContentLoaded', () => {
	const catalogue = document.querySelector('.catalogue-shell');
	if (!catalogue) {
		return;
	}

	const endpoint = catalogue.dataset.profilesEndpoint || '../api/profils.php';
	const convoquerEndpoint = catalogue.dataset.convoquerEndpoint || '../api/convoquer.php';
	const grid = document.getElementById('profilesGrid');
	const message = document.getElementById('catalogueMessage');
	const resultsMeta = document.getElementById('resultsMeta');
	const profilesCount = document.getElementById('profilesCount');
	const convoquesCount = document.getElementById('convoquesCount');
	const searchInput = document.getElementById('searchInput');
	const domainFilter = document.getElementById('domainFilter');
	const contractFilter = document.getElementById('contractFilter');
	const skillFilter = document.getElementById('skillFilter');
	const schoolFilter = document.getElementById('schoolFilter');
	const resetFilters = document.getElementById('resetFilters');

	let profiles = [];
	let convocationsCount = 0;

	const normalize = (value) => (value || '')
		.toString()
		.normalize('NFD')
		.replace(/[\u0300-\u036f]/g, '')
		.toLowerCase();

	const escapeHtml = (value) => (value || '')
		.toString()
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/'/g, '&#39;');

	const setMessage = (text, type = 'info') => {
		message.textContent = text;
		message.dataset.type = type;
		message.hidden = !text;
	};

	const syncCounters = (visibleCount) => {
		if (profilesCount) {
			profilesCount.textContent = String(visibleCount);
		}
		if (convoquesCount) {
			convoquesCount.textContent = String(convocationsCount);
		}
		if (resultsMeta) {
			resultsMeta.textContent = `${visibleCount} profil${visibleCount > 1 ? 's' : ''} trouvé${visibleCount > 1 ? 's' : ''}`;
		}
	};

	const getActiveFilters = () => ({
		search: normalize(searchInput.value),
		domain: normalize(domainFilter.value),
		contract: normalize(contractFilter.value),
		skill: normalize(skillFilter.value),
		school: normalize(schoolFilter.value),
	});

	const profileMatches = (profile, filters) => {
		const haystack = normalize([
			profile.name,
			profile.school,
			profile.promo,
			profile.bio,
			...(profile.domains || []),
			...(profile.contracts || []),
			...(profile.skills || []),
			...(profile.languages || []),
		].join(' '));

		if (filters.search && !haystack.includes(filters.search)) {
			return false;
		}

		if (filters.domain && !(profile.domains || []).some((domain) => normalize(domain).includes(filters.domain))) {
			return false;
		}

		if (filters.contract && !(profile.contracts || []).some((contract) => normalize(contract).includes(filters.contract))) {
			return false;
		}

		if (filters.skill && !(profile.skills || []).some((skill) => normalize(skill).includes(filters.skill))) {
			return false;
		}

		if (filters.school && normalize(profile.promoCode || profile.schoolCode || profile.schoolSlug || '') !== filters.school) {
			return false;
		}

		return true;
	};

	const renderChips = (items, className = 'chip') => items
		.map((item) => `<span class="${className}">${escapeHtml(item)}</span>`)
		.join('');

	const renderProfiles = () => {
		const filters = getActiveFilters();
		const visibleProfiles = profiles.filter((profile) => profileMatches(profile, filters));

		grid.innerHTML = visibleProfiles.length === 0
			? '<article class="empty-state"><h4>Aucun profil ne correspond à ces filtres.</h4><p>Essaie de supprimer un filtre ou d’élargir la recherche.</p></article>'
			: visibleProfiles.map((profile) => {
				const interviewDate = new Date();
				interviewDate.setDate(interviewDate.getDate() + 7);
				const defaultDate = interviewDate.toISOString().slice(0, 10);

				return `
					<article class="profile-card" data-profile-id="${profile.id}">
						<div class="profile-card__header">
							<div>
								<p class="profile-name">${escapeHtml(profile.name)}</p>
								<p class="profile-meta">${escapeHtml(profile.school)} • ${escapeHtml(profile.promo)}</p>
							</div>
							<span class="availability ${escapeHtml(profile.availabilityClass || 'open')}">${escapeHtml(profile.availability)}</span>
						</div>

						<p class="profile-bio">${escapeHtml(profile.bio)}</p>

						<div class="profile-info">
							<div>
								<span>Domaines</span>
								<div class="chip-list">${renderChips(profile.domains || [])}</div>
							</div>
							<div>
								<span>Contrats</span>
								<div class="chip-list">${renderChips(profile.contracts || [])}</div>
							</div>
							<div>
								<span>Compétences</span>
								<div class="chip-list">${renderChips(profile.skills || [])}</div>
							</div>
						</div>

						<div class="profile-footer">
							<div>
								<strong>${escapeHtml(profile.city || 'Campus JUNIA')}</strong>
								<span>${escapeHtml((profile.languages || []).join(' • '))}</span>
							</div>
							<button type="button" class="convoquer-trigger" data-action="toggle-convoquer" data-target="convoquer-${profile.id}">Convoquer</button>
						</div>

						<form class="convoquer-form" id="convoquer-${profile.id}" data-profile-id="${profile.id}" hidden>
							<label>
								<span>Date d’entretien</span>
								<input type="date" name="date" value="${defaultDate}" required>
							</label>
							<label>
								<span>Message</span>
								<textarea name="message" rows="3" placeholder="Précise le créneau et les informations utiles"></textarea>
							</label>
							<div class="profile-footer profile-footer--form">
								<button type="submit" class="button button--small">Envoyer la convocation</button>
								<button type="button" class="secondary-button button--small" data-action="close-convoquer">Annuler</button>
							</div>
						</form>
					</article>
				`;
			}).join('');

		syncCounters(visibleProfiles.length);
	};

	const loadProfiles = async () => {
		setMessage('Chargement des profils...', 'info');

		try {
			const response = await fetch(endpoint, {
				headers: {
					'Accept': 'application/json'
				}
			});

			const data = await response.json().catch(() => null);

			if (!response.ok || !data || !data.success) {
				throw new Error((data && data.message) ? data.message : 'Impossible de charger le catalogue.');
			}

			profiles = Array.isArray(data.profils) ? data.profils : [];
			setMessage('', 'info');
			renderProfiles();
		} catch (error) {
			grid.innerHTML = '';
			setMessage(error.message || 'Le catalogue est indisponible.', 'error');
			syncCounters(0);
		}
	};

	const submitConvocation = async (form) => {
		const profileId = form.dataset.profileId;
		const dateField = form.querySelector('input[name="date"]');
		const messageField = form.querySelector('textarea[name="message"]');
		const submitButton = form.querySelector('button[type="submit"]');

		if (!profileId || !dateField || !dateField.value) {
			setMessage('Merci de choisir une date de convocation.', 'error');
			return;
		}

		if (submitButton) {
			submitButton.disabled = true;
			submitButton.textContent = 'Envoi...';
		}

		try {
			const response = await fetch(convoquerEndpoint, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'Accept': 'application/json'
				},
				body: JSON.stringify({
					profileId,
					date: dateField.value,
					message: messageField ? messageField.value.trim() : ''
				})
			});

			const data = await response.json().catch(() => null);

			if (!response.ok || !data || !data.success) {
				throw new Error((data && data.message) ? data.message : 'La convocation a échoué.');
			}

			convocationsCount += 1;
			syncCounters(document.querySelectorAll('.profile-card').length);
			setMessage(data.message || 'Convocation envoyée avec succès.', 'success');
			form.hidden = true;
			form.reset();
			renderProfiles();
		} catch (error) {
			setMessage(error.message || 'Impossible d’envoyer la convocation.', 'error');
		} finally {
			if (submitButton) {
				submitButton.disabled = false;
				submitButton.textContent = 'Envoyer la convocation';
			}
		}
	};

	grid.addEventListener('click', (event) => {
		const toggleButton = event.target.closest('[data-action="toggle-convoquer"]');
		const closeButton = event.target.closest('[data-action="close-convoquer"]');

		if (toggleButton) {
			const target = document.getElementById(toggleButton.dataset.target);
			if (target) {
				target.hidden = !target.hidden;
			}
		}

		if (closeButton) {
			const form = closeButton.closest('.convoquer-form');
			if (form) {
				form.hidden = true;
			}
		}
	});

	grid.addEventListener('submit', (event) => {
		const form = event.target.closest('.convoquer-form');
		if (!form) {
			return;
		}

		event.preventDefault();
		submitConvocation(form);
	});

	[searchInput, domainFilter, contractFilter, skillFilter, schoolFilter].forEach((control) => {
		control.addEventListener('input', renderProfiles);
		control.addEventListener('change', renderProfiles);
	});

	resetFilters.addEventListener('click', () => {
		searchInput.value = '';
		domainFilter.value = '';
		contractFilter.value = '';
		skillFilter.value = '';
		schoolFilter.value = '';
		renderProfiles();
	});

	loadProfiles();
});
