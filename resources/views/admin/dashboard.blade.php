@extends('layouts.templateDashboard')
@section('title', 'Administrateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Vue d'ensemble - Administration</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs</h5>
                        <canvas id="userStatusChart"></canvas>
                    </div>    
                </div>
            </div>

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

            <div class="col-lg-12">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title">Répartition des rôles</h5>
                        <canvas id="rolesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card m-3">
                    <div class="card-body">
                        <h5 class="card-title text-center">Candidatures Bénévoles</h5>
                        <canvas id="benevoleStatusChart"></canvas>
                    </div>
                </div>
            </div>

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
canvas {
    display: block;
    width: 90% !important; 
    height: 90% !important; 
    aspect-ratio: 16 / 9; 
    margin: 0 auto; 
}

.card {
    border: none; 
    border-radius: 12px; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
    transition: transform 0.3s ease, box-shadow 0.3s ease; 
    overflow: hidden; 
    background-color: #f8f9fa;
}

.card:hover {
    transform: translateY(-5px); 
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15); 
}


.card-body {
    padding: 15px 20px; 
    background-color: #ffffff; 
    display: flex; 
    flex-direction: column;
    justify-content: center; 
    text-align: center; 
    height: 300px; 
}

.col-lg-12 .card-body {
    height: 400px; 
    padding-top: 10px; 
    position: relative;
}

.card-body canvas {
    margin-top: auto; 
    margin-bottom: 0; 
}

.col-lg-12 .card-body {
    height: 400px; 
    padding-top: 10px;
    padding-bottom: 10px; 
}

@media (max-width: 768px) {
    .card-body {
        height: auto; 
        padding: 15px;
    }

    .card-title {
        font-size: 1.1rem; 
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
        userStatusLabels: ['Active', 'Total'], 
        userStatusData: [@json($activeUsersCount), @json($usersTotalCount)], 

        benevoleStatusLabels: @json(array_keys($benevoleStatusData)), 
        benevoleStatusData: @json(array_values($benevoleStatusData)),
        
        commercantStatusLabels: @json(array_keys($commercantStatusData)), 
        commercantStatusData: @json(array_values($commercantStatusData)) 
    };
</script>

    
</script>


<script src="{{ asset('js/dashboard.js') }}"></script>


@endsection
