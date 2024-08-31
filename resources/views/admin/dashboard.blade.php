@extends('layouts.templateDashboard')
@section('title', 'Administrateur | NoMoreWaste')

@section('content')
<!-- Main Content -->
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Vue d'ensemble - Administration</h1>
    <div class="container-fluid">
        <div class="row">
            <!-- Card for Active Users (full width) -->
            <div class="col-lg-12">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs</h5>
                        <canvas id="userStatusChart"></canvas>
                    </div>    
                </div>
            </div>

            <!-- Row with two cards -->
            <div class="col-lg-6">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title">Evolution des inscriptions</h5>
                        <canvas id="registrationsChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title">Evolution des bannissements</h5>
                        <canvas id="bansChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Full-width card for Role Distribution -->
            <div class="col-lg-12">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title">Répartition des rôles</h5>
                        <canvas id="rolesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Candidatures des bénévoles -->
            <div class="col-lg-6 col-md-12">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title text-center">Candidatures Bénévoles</h5>
                        <canvas id="benevoleStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Candidatures des commerçants -->
            <div class="col-lg-6 col-md-12">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title text-center">Candidatures Commerçants</h5>
                        <canvas id="commercantStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<style>
/* Style global pour les graphiques */
canvas {
    display: block;
    width: 90% !important; /* Le canvas prend toute la largeur de son conteneur */
    height: 90% !important; /* Le canvas prend toute la hauteur de son conteneur */
    aspect-ratio: 16 / 9; /* Maintient un ratio d'aspect pour les graphiques */
    margin: 0 auto; /* Centre le canvas horizontalement */
}

/* Style des cartes */
.card {
    border: none; /* Supprime les bordures par défaut */
    border-radius: 12px; /* Coins arrondis des cartes */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ombre douce pour un effet de profondeur */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animation douce au survol */
    overflow: hidden; /* Évite que le contenu dépasse */
    background-color: #f8f9fa; /* Fond léger pour contraster avec le canvas */
}

.card:hover {
    transform: translateY(-5px); 
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15); 
}

/* Style du contenu des cartes */
.card-body {
    padding: 15px 20px; /* Ajoute du padding pour espacer le contenu */
    background-color: #ffffff; 
    display: flex; /* Flexbox pour centrer le contenu */
    flex-direction: column; /* Aligne les éléments verticalement */
    justify-content: center; /* Centre le contenu verticalement */
    text-align: center; /* Centre le texte */
    height: 300px; 
}

/* Style spécifique pour les cartes pleine largeur */
.col-lg-12 .card-body {
    height: 400px; /* Hauteur ajustée pour éviter que ce soit trop grand */
    padding-top: 10px; /* Ajoute un peu plus d'espace en haut */
    position: relative; /* Permet de contrôler la taille des enfants */
}

/* Ajustement des légendes pour qu'elles soient toujours visibles */
.card-body canvas {
    margin-top: auto; /* Pousse le canvas vers le bas */
    margin-bottom: 0; /* Retire l'espace en dessous du canvas */
}

/* Ajustement pour la hauteur des cartes .col-lg-12 */
.col-lg-12 .card-body {
    height: 400px; /* Hauteur fixe pour les grandes cartes */
    padding-top: 10px; /* Ajoute un peu plus d'espace en haut */
    padding-bottom: 10px; /* Ajoute un peu plus d'espace en bas */
}

/* Style responsive pour les écrans plus petits */
@media (max-width: 768px) {
    .card-body {
        height: auto; /* Hauteur automatique pour les petites tailles d'écran */
        padding: 15px; /* Réduction du padding pour les petits écrans */
    }

    .card-title {
        font-size: 1.1rem; /* Taille de police légèrement réduite sur mobile */
    }
}
</style>

<script>
    window.dashboardData = {
        months: @json(array_values($months)),
        registrationData: @json($registrationData),
        bansData: @json($bansData),
        roleLabels: @json($roleLabels),
        roleCounts: @json($roleCounts),
        userStatusLabels: ['Active', 'Total'], // Labels pour les statuts d'utilisateur
        userStatusData: [@json($activeUsersCount), @json($usersTotalCount)], // Données pour les statuts d'utilisateur

        // Nouvelles données pour les candidatures bénévoles et commerçants
        benevoleStatusLabels: @json(array_keys($benevoleStatusData)), // Labels pour les statuts des candidatures bénévoles
        benevoleStatusData: @json(array_values($benevoleStatusData)), // Données pour les statuts des candidatures bénévoles
        
        commercantStatusLabels: @json(array_keys($commercantStatusData)), // Labels pour les statuts des candidatures commerçants
        commercantStatusData: @json(array_values($commercantStatusData)) // Données pour les statuts des candidatures commerçants
    };
</script>

    
</script>


<script src="{{ asset('js/dashboard.js') }}"></script>


@endsection
