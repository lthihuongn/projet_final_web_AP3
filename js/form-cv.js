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
                if (!currentState[input.name]) {
                    // Les checkbox comme "domains[]" ont besoin d'un tableau
                    // On retire les crochets pour simplifier la clé côté JS/PHP si besoin, 
                    // mais ici on garde input.name car ton formulaire utilise name="domains[]"
                    let key = input.name.replace('[]', ''); 
                    if (!currentState[key]) {
                        currentState[key] = [];
                    }
                }
                
                let key = input.name.replace('[]', '');
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
            // On vérifie s'il s'agit d'une checkbox avec un tableau
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
        // Le localStorage peut être bloqué par les paramètres de confidentialité du navigateur
        console.warn('Impossible de charger le brouillon.');
    }

    // 2. Sauvegarde automatique en local lors de la saisie
    formInputs.forEach((input) => {
        input.addEventListener('input', () => {
            try {
                localStorage.setItem(draftStorageKey, JSON.stringify(getFormState()));
            } catch (error) {
                // Ignorer les erreurs de stockage silencieusement
            }
        });
    });

    // 3. Gestion de la soumission du formulaire vers l'API PHP
    profileForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Enregistrement en cours...';
        }

        // Récupération des données formatées
        const payload = getFormState();

        try {
            // Envoi de la requête HTTP POST au serveur
            const response = await fetch('../api/enregistrer-cv.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            // Tentative de lecture du retour JSON (même en cas d'erreur HTTP 400/500)
            const responseData = await response.json().catch(() => null);

            // Vérification du succès de la requête et de la réponse applicative
            if (!response.ok || !responseData || !responseData.success) {
                throw new Error((responseData && responseData.message) ? responseData.message : 'Erreur de communication avec le serveur.');
            }

            // Succès : on informe l'étudiant et on efface le brouillon local
            updateNotification(responseData.message, false);
            localStorage.removeItem(draftStorageKey);

        } catch (error) {
            // Échec : on affiche l'erreur en rouge
            updateNotification(error.message, true);
        } finally {
            // Réactivation du bouton de soumission
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Enregistrer mon profil';
            }
        }
    });
});