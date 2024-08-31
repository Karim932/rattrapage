<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur 422 - Entité non traitable</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <i class="fas fa-exclamation-circle text-red-600 text-8xl"></i>
        <h1 class="text-6xl font-bold text-red-600 mt-4">Erreur 422</h1>
        <p class="text-xl mt-4">Les données fournies ne peuvent pas être traitées. Comme un hamburger sans pain !</p>
        <p class="mt-2 text-gray-600">Astuce : Vérifiez vos données et essayez à nouveau.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
            Retour à l'accueil
        </a>
    </div>
</body>
</html>
