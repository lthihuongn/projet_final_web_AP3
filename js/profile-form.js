document.addEventListener('DOMContentLoaded', () => {
    const profileForm = document.getElementById('profilForm');
    
    if (!profileForm) {
        return;
    }

    const draftStorageKey = 'junia-profile-draft';
    const notificationNode = document.querySelector('.profil-note');
    const submitButton = profileForm.querySelector('button[type="submit"]');
    const formInputs = Array.from(profileForm.querySelectorAll('input, textarea, select'));

    // Met à jour le message d'information avec changement de couleur en cas d'erreur
    const updateNotification = (message, isError = false) => {
        if (notificationNode) {
            notificationNode.textContent = message;
            notificationNode.style.color = isError ? '#9f1239' : '#0f7a53';
        }
    };

    // Lit l'état actuel du formulaire pour construire le payload (et le brouillon)
    const getFormState = () => {
        const currentState = {};
        
        formInputs.forEach((input) => {
            if (input.type === 'checkbox') {
                let key = input.name.replace('[]', ''); 
                if (!currentState[key]) {
                    currentState[key] = [];
                }
                if (input.checked) {
                    currentState[key].push(input.value);
                }
                return;
            }

            currentState[input.name] = input.value;
        });
        
        return currentState;
    };

    // Restaure l'état du formulaire depuis le brouillon local
    const restoreFormState = (savedState) => {
        formInputs.forEach((input) => {
            let key = input.name.replace('[]', '');
            const savedValue = savedState[key] !== undefined ? savedState[key] : savedState[input.name];
            
            if (input.type === 'checkbox') {
                input.checked = Array.isArray(savedValue) && savedValue.includes(input.value);
                return;
            }

            if (typeof savedValue === 'string') {
                input.value = savedValue;
            }
        });
    };

    // 1. Chargement du brouillon au démarrage
    try {
        const savedDraft = localStorage.getItem(draftStorageKey);
        if (savedDraft) {
            restoreFormState(JSON.parse(savedDraft));
            updateNotification('Brouillon chargé depuis le navigateur.');
        }
    } catch (error) {
        console.warn('Impossible de charger le brouillon.');
    }

    // 2. Sauvegarde automatique en local lors de la saisie
    formInputs.forEach((input) => {
        input.addEventListener('input', () => {
            try {
                localStorage.setItem(draftStorageKey, JSON.stringify(getFormState()));
            } catch (error) {}
        });
    });

    // 3. Gestion de la soumission du formulaire vers l'API PHP
    profileForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Enregistrement en cours...';
        }

        const payload = getFormState();

        try {
            // Envoi vers le nouveau fichier d'API
            const response = await fetch('../api/save-cv.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const responseData = await response.json().catch(() => null);

            if (!response.ok || !responseData || !responseData.success) {
                throw new Error((responseData && responseData.message) ? responseData.message : 'Erreur de communication avec le serveur.');
            }

            // Succès
            updateNotification(responseData.message, false);
            localStorage.removeItem(draftStorageKey);

        } catch (error) {
            updateNotification(error.message, true);
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Enregistrer mon profil';
            }
        }
    });
});