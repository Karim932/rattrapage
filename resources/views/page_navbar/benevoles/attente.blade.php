<x-app-layout>
    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">{{ session('success') }}</strong>
                    <span class="block sm:inline">{{ session('success_message') }}</span>
                </div>
            @endif

            @if (session('refused'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Attention:</strong>
                    <span class="block sm:inline">{{ session('refused') }}</span>
                </div>
            @endif

            @if (session('revoked'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Attention:</strong>
                    <span class="block sm:inline">{{ session('revoked') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if ($answers->isEmpty() && $candidature->status === 'en attente')
                <div class="text-center py-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Candidature en attente</h1>
                    <p class="text-lg text-gray-600">Votre candidature est en cours de traitement par l'équipe No More Waste. Vous serez informé une fois qu'une décision sera prise.</p>
                </div>
            @elseif ($candidature->status === 'refusé')
                <div class="text-center py-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Candidature refusée</h1>
                    <p class="text-lg text-red-600">Votre candidature a été refusée. Malheureusement, malgré que votre profil soit très intéressant, il vous manque un petit quelque chose.</p>
                    <p class="text-lg text-red-600">Pour plus de détails, veuillez contacter l'administration.</p>
                </div>

            @elseif ($candidature->status === 'revoqué')
                <div class="text-center py-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Candidature révoquée</h1>
                    <p class="text-lg text-yellow-600">Votre candidature a été révoquée. Veuillez contacter l'administration pour plus d'informations.</p>
                </div>
            @elseif ($candidature->status === 'accepté')
                <div class="text-center py-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4">Félicitations!</h1>
                    <p class="text-lg text-green-600 mb-10">Votre candidature a été acceptée. Bienvenue dans l'équipe!</p>
                    <div class="text-center mt-8">
                        <a href="{{ route('dashboard.benevole') }}" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" role="button">
                            Aller au tableau de bord
                        </a>
                    </div>
                </div>
            @else
                <h1 class="text-3xl font-bold text-gray-800 mb-8">Réponses de la Candidature</h1>
                @forelse ($answers as $answer)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                        <div class="px-4 py-5 sm:px-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Message reçu le {{ $answer->created_at->format('d M Y à H:i') }}</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Voici les détails du message envoyé par l'administrateur.</p>
                        </div>
                        <div class="border-t border-gray-200">
                            <dl>
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Titre du message</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $answer->titre }}</dd>
                                </div>
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Contenu du message</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">{{ $answer->message }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">Aucun message n'a été reçu pour cette candidature.</p>
                @endforelse

                <!-- Bouton Modifier conditionnellement affiché -->
                @if(!session('success') && $candidature->status == 'en cours')
                    <a href="{{ route('change.benevole') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" role="button">
                        Modifier ma candidature
                    </a>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
