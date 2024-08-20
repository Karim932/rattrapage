{{-- resources/views/skills/index.blade.php --}}
@extends('layouts.templateAdmin')

@section('title', 'Compétences')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <div class="container mx-auto px-4 sm:px-8">
        <div class="py-8">
            <div>
                <h2 class="text-2xl py-2 font-semibold leading-tight">Gérer les Compétences</h2>
            </div>
            <div class="my-4 py-4 flex justify-between items-center w-full">
                <button type="button" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 ease-in-out" onclick="location.href='{{ route('services.index') }}';">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour
                </button>

                <button onclick="toggleModal('add-skill-modal')" class="text-white bg-blue-500 hover:bg-blue-700 text-sm font-semibold py-2 px-4 rounded inline-flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter Compétence
                </button>
            </div>



            <!-- Modal pour ajouter une compétence -->
            <div id="add-skill-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center">
                <div id="main-content" class="flex-1 ml-64 p-10 transition-all">
                    <div class="relative p-5 border w-auto sm:w-96 shadow-lg rounded-md bg-white">
                        <div class="text-center">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Nouvelle Compétence</h3>
                            <div class="mt-2 px-7 py-3">
                                <form action="{{ route('skills.store') }}" method="POST">
                                    @csrf
                                    <input type="text" name="name" placeholder="Nom de la compétence" class="mb-4 px-4 py-2 border rounded-md w-full">
                                    <div class="flex justify-center space-x-4">
                                        <button type="submit" class="inline-flex justify-center px-4 py-2 text-white bg-green-500 border border-transparent rounded-md hover:bg-green-700 focus:outline-none focus:border-green-900 focus:ring focus:ring-green-300">
                                            Créer
                                        </button>
                                        <button type="button" onclick="toggleModal('add-skill-modal')" class="inline-flex justify-center px-4 py-2 text-white bg-red-500 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:border-red-900 focus:ring focus:ring-red-300">
                                            Annuler
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tableau des compétences -->
            <div class="align-middle inline-block min-w-full shadow overflow-hidden bg-white shadow-dashboard px-8 pt-3 rounded-bl-lg rounded-br-lg">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Nom de la compétence
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skills as $skill)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    {{ $skill->name }}
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <form action="{{ route('skills.destroy', $skill->id) }}" method="POST" class="delete-user-form inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleModal(modalID){
    document.getElementById(modalID).classList.toggle('hidden');
    document.getElementById(modalID).classList.toggle('flex');
}
</script>
@endsection
