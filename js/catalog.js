document.addEventListener('DOMContentLoaded', () => {
	const catalogShell = document.querySelector('.catalogue-shell');
	if (!catalogShell) {
		return;
	}

	// Nouveaux chemins d'API
	const endpoint = catalogShell.dataset.profilesEndpoint || '../api/profiles.php';
	const interviewEndpoint = catalogShell.dataset.convoquerEndpoint || '../api/interview.php';
	
	const grid = document.getElementById('profilesGrid');
	const messageNode = document.getElementById('catalogueMessage');
	const resultsMeta = document.getElementById('resultsMeta');
	const profilesCount = document.getElementById('profilesCount');
	const interviewCountNode = document.getElementById('convoquesCount');
	
	const searchInput = document.getElementById('searchInput');
	const domainFilter = document.getElementById('domainFilter');
	const contractFilter = document.getElementById('contractFilter');
	const skillFilter = document.getElementById('skillFilter');
	const schoolFilter = document.getElementById('schoolFilter');
	const resetFiltersBtn = document.getElementById('resetFilters');

	let profilesList = [];
	let sentInterviewsCount = 0;

	const normalizeText = (value) => (value || '')
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

	const displayMessage = (text, type = 'info') => {
		messageNode.textContent = text;
		messageNode.dataset.type = type;
		messageNode.hidden = !text;
	};

	const syncCounters = (visibleCount) => {
		if (profilesCount) profilesCount.textContent = String(visibleCount);
		if (interviewCountNode) interviewCountNode.textContent = String(sentInterviewsCount);
		if (resultsMeta) resultsMeta.textContent = `${visibleCount} profil${visibleCount > 1 ? 's' : ''} trouvé${visibleCount > 1 ? 's' : ''}`;
	};

	const getActiveFilters = () => ({
		search: normalizeText(searchInput.value),
		domain: normalizeText(domainFilter.value),
		contract: normalizeText(contractFilter.value),
		skill: normalizeText(skillFilter.value),
		school: normalizeText(schoolFilter.value),
	});

	const profileMatches = (profile, filters) => {
		const haystack = normalizeText([
			profile.name, profile.school, profile.promo, profile.bio,
			...(profile.domains || []), ...(profile.contracts || []),
			...(profile.skills || []), ...(profile.languages || []),
		].join(' '));

		if (filters.search && !haystack.includes(filters.search)) return false;
		if (filters.domain && !(profile.domains || []).some((d) => normalizeText(d).includes(filters.domain))) return false;
		if (filters.contract && !(profile.contracts || []).some((c) => normalizeText(c).includes(filters.contract))) return false;
		if (filters.skill && !(profile.skills || []).some((s) => normalizeText(s).includes(filters.skill))) return false;
		if (filters.school && normalizeText(profile.promoCode || profile.schoolCode || '') !== filters.school) return false;

		return true;
	};

	const renderChips = (items, className = 'chip') => items
		.map((item) => `<span class="${className}">${escapeHtml(item)}</span>`)
		.join('');

	const renderProfiles = () => {
		const filters = getActiveFilters();
		const visibleProfiles = profilesList.filter((profile) => profileMatches(profile, filters));

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
		displayMessage('Chargement des profils...', 'info');

		try {
			const response = await fetch(endpoint, {
				headers: { 'Accept': 'application/json' }
			});

			const data = await response.json().catch(() => null);

			if (!response.ok || !data || !data.success) {
				throw new Error((data && data.message) ? data.message : 'Impossible de charger le catalogue.');
			}

			profilesList = Array.isArray(data.profils) ? data.profils : [];
			displayMessage('', 'info');
			renderProfiles();
		} catch (error) {
			grid.innerHTML = '';
			displayMessage(error.message || 'Le catalogue est indisponible.', 'error');
			syncCounters(0);
		}
	};

	const processInterviewForm = async (form) => {
		const profileId = form.dataset.profileId;
		const dateField = form.querySelector('input[name="date"]');
		const messageField = form.querySelector('textarea[name="message"]');
		const submitBtn = form.querySelector('button[type="submit"]');

		if (!profileId || !dateField || !dateField.value) {
			displayMessage('Merci de choisir une date de convocation.', 'error');
			return;
		}

		if (submitBtn) {
			submitBtn.disabled = true;
			submitBtn.textContent = 'Envoi...';
		}

		try {
			const response = await fetch(interviewEndpoint, {
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

			sentInterviewsCount += 1;
			syncCounters(document.querySelectorAll('.profile-card').length);
			displayMessage(data.message || 'Convocation envoyée avec succès.', 'success');
			form.hidden = true;
			form.reset();
			renderProfiles();
		} catch (error) {
			displayMessage(error.message || 'Impossible d’envoyer la convocation.', 'error');
		} finally {
			if (submitBtn) {
				submitBtn.disabled = false;
				submitBtn.textContent = 'Envoyer la convocation';
			}
		}
	};

	grid.addEventListener('click', (event) => {
		const toggleBtn = event.target.closest('[data-action="toggle-convoquer"]');
		const closeBtn = event.target.closest('[data-action="close-convoquer"]');

		if (toggleBtn) {
			const target = document.getElementById(toggleBtn.dataset.target);
			if (target) target.hidden = !target.hidden;
		}

		if (closeBtn) {
			const form = closeBtn.closest('.convoquer-form');
			if (form) form.hidden = true;
		}
	});

	grid.addEventListener('submit', (event) => {
		const form = event.target.closest('.convoquer-form');
		if (!form) return;

		event.preventDefault();
		processInterviewForm(form);
	});

	[searchInput, domainFilter, contractFilter, skillFilter, schoolFilter].forEach((control) => {
		control.addEventListener('input', renderProfiles);
		control.addEventListener('change', renderProfiles);
	});

	resetFiltersBtn.addEventListener('click', () => {
		searchInput.value = '';
		domainFilter.value = '';
		contractFilter.value = '';
		skillFilter.value = '';
		schoolFilter.value = '';
		renderProfiles();
	});

	loadProfiles();
});