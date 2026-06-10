document.addEventListener('DOMContentLoaded', () => {
	const form = document.getElementById('profilForm');
	if (!form) {
		return;
	}

	const storageKey = 'junia-profile-draft';
	const note = document.querySelector('.profil-note');

	const fields = Array.from(form.querySelectorAll('input, textarea, select'));

	const readState = () => {
		const state = {};
		fields.forEach((field) => {
			if (field.type === 'checkbox') {
				if (!state[field.name]) {
					state[field.name] = [];
				}
				if (field.checked) {
					state[field.name].push(field.value);
				}
				return;
			}

			state[field.name] = field.value;
		});
		return state;
	};

	const applyState = (state) => {
		fields.forEach((field) => {
			const value = state[field.name];
			if (field.type === 'checkbox') {
				field.checked = Array.isArray(value) && value.includes(field.value);
				return;
			}

			if (typeof value === 'string') {
				field.value = value;
			}
		});
	};

	const updateNote = (message) => {
		if (note) {
			note.textContent = message;
		}
	};

	try {
		const saved = localStorage.getItem(storageKey);
		if (saved) {
			applyState(JSON.parse(saved));
			updateNote('Brouillon chargé depuis ce navigateur.');
		}
	} catch (error) {
		updateNote('Impossible de charger le brouillon local.');
	}

	form.addEventListener('submit', (event) => {
		event.preventDefault();

		try {
			localStorage.setItem(storageKey, JSON.stringify(readState()));
			updateNote('Profil enregistré localement.');
		} catch (error) {
			updateNote('Sauvegarde locale indisponible sur ce navigateur.');
		}
	});

	fields.forEach((field) => {
		field.addEventListener('input', () => {
			try {
				localStorage.setItem(storageKey, JSON.stringify(readState()));
			} catch (error) {
				/* ignore storage errors */
			}
		});
	});
});