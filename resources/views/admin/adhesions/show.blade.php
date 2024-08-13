@extends('layouts.templateAdmin')
@section('title', 'Administrateur | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl sm:rounded-lg p-5">
            <div id="tabs" class="px-3">
                <button id="detailsTab" class="nav-history active" data-tab="details">Détails de la Candidature</button>
                <button id="messagesTab" class="nav-history" data-id ="messages">Historique de la demande</button>
            </div>

            <div id="detailsContent" style="display: Block;">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 p-3 px-3">
                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-700">Informations Générales:</h3>
                        <p><strong>Nom/Entreprise:</strong>

                            @if ($candidature instanceof App\Models\AdhesionCommercant)
                                {{ $candidature->company_name }}
                            @elseif ($candidature instanceof App\Models\AdhesionBenevole)
                                {{ $candidature->user->firstname . ' ' . $candidature->user->lastname }}
                            @endif
                        </p>
                        <p><strong>ID:</strong> {{ $adhesion->id }}</p>
                        <p><strong>ID:</strong> {{ $candidature->user->role }}</p>
                        <p><strong>Email:</strong> {{ $candidature->user->email }}</p>
                        <p><strong>Statut:</strong> {{ $candidature->status }}</p>
                        <p><strong>Active:</strong> {{ $candidature->is_active ? 'Oui' : 'Non' }}</p>
                    </div>

                    @if ($candidature instanceof App\Models\AdhesionCommercant)
                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-700">Détails Commerçant:</h3>
                        <p><strong>SIRET:</strong> {{ $candidature->siret }}</p>
                        <p><strong>Adresse:</strong> {{ $candidature->address }}</p>
                        <p><strong>Ville:</strong> {{ $candidature->city }}</p>
                        <p><strong>Code Postal:</strong> {{ $candidature->postal_code }}</p>
                        <p><strong>Pays:</strong> {{ $candidature->country }}</p>
                        <p><strong>Type de produits:</strong> {{ $candidature->product_type }}</p>
                        <p><strong>Horaires d'ouverture:</strong> {{ $candidature->opening_hours }}</p>
                        <p><strong>Fréquence de participation:</strong> {{ $candidature->participation_frequency }}</p>
                        <p><strong>Notes:</strong> {{ $candidature->notes }}</p>
                    </div>
                    @elseif ($candidature instanceof App\Models\AdhesionBenevole)
                    <div class="bg-gray-50 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-gray-700">Détails Bénévole:</h3>
                        <p><strong>Ancien bénévole:</strong> {{ $candidature->old_benevole ? 'Oui' : 'Non' }}</p>
                        <p><strong>Motivation:</strong> {{ $candidature->motivation }}</p>
                        <p><strong>Expérience:</strong> {{ $candidature->experience }}</p>
                        <p><strong>Disponibilité début:</strong> {{ $candidature->availability_begin ? \Carbon\Carbon::parse($candidature->availability_begin)->format('d/m/Y') : 'Non défini' }}</p>
                        <p><strong>Disponibilité fin:</strong> {{ $candidature->availability_end ? \Carbon\Carbon::parse($candidature->availability_end)->format('d/m/Y') : 'Non défini' }}</p>
                        <p><strong>Heures par mois:</strong> {{ $candidature->hour_month }}</p>
                        <p><strong>Permis de conduire:</strong> {{ $candidature->permis ? 'Oui' : 'Non' }}</p>
                        <p><strong>Notes supplémentaires:</strong> {{ $candidature->additional_notes }}</p>
                    </div>
                    @endif
                </div>

                <div class="flex justify-between mt-8">
                    <a href="{{ route('adhesion.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Retour
                    </a>

                    <div class="relative" id="dropdownContainer">
                        <button id="dropdownButton" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                            Actions
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="dropdownMenu" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none">
                            <div class="py-1">
                                <a href="{{ route('adhesion.edit', $candidature->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Modifier</a>
                                <form method="POST" action="{{ route('adhesion.destroy', $adhesion->id) }}" class="delete-user-form block text-left">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"  class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                        Supprimer
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.adhesion.accept', $adhesion->id) }}" class="accept-user-form block text-left">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-gray-100">
                                        Accepter
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.adhesion.refuse', $adhesion->id) }}" class="refuse-user-form block text-left">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                        Refuser
                                    </button>
                                </form>
                                @if ($candidature->status === 'accepté')
                                    <form method="POST" action="{{ route('admin.adhesion.revoque', $adhesion->id) }}" class="revoque-user-form block text-left">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-gray-100">
                                            Révoquer (Role → User)
                                        </button>
                                    </form>
                                @endif
                                <button type="button" class="w-full text-left px-4 py-2 text-sm text-blue-700 hover:bg-blue-100 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out" onclick="openModal()">
                                    Répondre
                                </button>

                                <div id="responseModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-900 bg-opacity-25 transition-opacity"></div>

                                        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                            <form action="{{ route('answer.store', $adhesion->id) }}" method="POST">
                                                @csrf
                                                <div class="bg-white px-6 py-5 sm:p-6">
                                                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modal-title">Réponse à la candidature</h3>
                                                    <div>
                                                        <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                                        <textarea id="message" name="message" required class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300 ease-in-out" placeholder="Votre message..."></textarea>
                                                    </div>
                                                    <div class="mt-4">
                                                        <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                                                        <input type="text" name="titre" id="titre" required class="form-input mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition duration-300 ease-in-out" placeholder="Titre de votre réponse..." value="{{ old('titre') }}">
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent px-4 py-2 text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-300 ease-in-out">
                                                        Envoyer
                                                    </button>
                                                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 px-4 py-2 text-base font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-300 ease-in-out">
                                                        Annuler
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="messagesContent" style="display: None;">
                <div id="messagesContent">
                    @if ($adhesion->answers->isEmpty())
                        <p class="text-md text-red-700 mt-2"> Aucune réponse n'a été trouvée pour cette candidature.</p>
                    @else
                        @foreach ($answers as $answer)
                            <div class="message bg-white p-4 rounded-lg shadow-md mt-4">
                                <h3 class="text-lg font-semibold text-blue-600">Titre : {{ $answer->titre }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Créé le {{ $answer->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-md text-gray-700 mt-2">Contenu : {{ $answer->message }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
