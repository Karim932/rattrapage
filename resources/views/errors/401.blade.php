<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erreur 401 - Non Autorisé</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="text-center">
        <i class="fas fa-lock text-red-600 text-8xl"></i>
        <h1 class="text-6xl font-bold text-red-600 mt-4">Erreur 401</h1>
        <p class="text-xl mt-4">Vous n'êtes pas autorisé à accéder à cette page. On ne laisse pas entrer n'importe qui !</p>
        <p class="mt-2 text-gray-600">Astuce : Peut-être que vous avez oublié vos identifiants ?</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
            Retour à l'accueil
        </a>
    </div>
</body>
</html>
