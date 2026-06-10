<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>

    <!-- Polices JUNIA -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --junia-violet: #6B2C91;
            --junia-orange: #F39200;
            --junia-violet-clair: #A569BD;
            --junia-violet-fonce: #4A1B68;
            --junia-fond: #FAF7F2;
            --junia-blanc: #FFFFFF;
            --junia-bordure: #E5DCED;
        }

        body {
            background: var(--junia-fond);
            font-family: 'Open Sans', sans-serif;
        }

        h1, h2, h3, h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: var(--junia-violet-fonce);
        }

        .carte-profil {
            background: var(--junia-blanc);
            border: 1px solid var(--junia-bordure);
            border-top: 4px solid var(--junia-violet);
            border-radius: 12px;
            padding: 1.5rem;
            max-width: 900px;
            margin: 40px auto;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .photo-profil {
            width: 150px;
            height: 150px;
            border-radius: 100%;
            object-fit: cover;
            border: 4px solid var(--junia-violet);
        }

        .btn-primaire {
            background: linear-gradient(135deg, var(--junia-violet), var(--junia-orange));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primaire:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(107,44,145,0.3);
        }

        .badge-junia {
            background: var(--junia-violet-clair);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-right: 5px;
        }
    </style>
</head>

<body>

<div class="carte-profil">

    <div class="text-center">
        <img src="https://via.placeholder.com/150" class="photo-profil" alt="Photo de profil">
        <h2 class="mt-3">Prénom Nom</h2>
        <p class="text-muted">email@example.com</p>

        <button class="btn-primaire mt-2">Modifier mon profil</button>
    </div>

    <hr class="my-4">

    <h3>Biographie</h3>
    <p>
        Ceci est un exemple de biographie conforme à la charte graphique JUNIA.
    </p>

    <h3 class="mt-4">Compétences</h3>
    <ul>
        <li>HTML / CSS</li>
        <li>PHP</li>
        <li>MySQL</li>
    </ul>

    <h3 class="mt-4">Expériences professionnelles</h3>
    <div class="card p-3 mb-2">
        <h5>Développeur Web</h5>
        <p class="text-muted">Entreprise X — 2023</p>
        <p>Description de l’expérience.</p>
    </div>

    <h3 class="mt-4">Domaines recherchés</h3>
    <span class="badge-junia">Stage</span>
    <span class="badge-junia">Alternance</span>

</div>

</body>
</html>
