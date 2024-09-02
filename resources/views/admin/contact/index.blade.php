@extends('layouts.templateAdmin')
@section('title', 'Contact | NoMoreWaste')

@section('content')
<div id="main-content" class="flex-1 ml-64 p-10 transition-all">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Tickets de Contact</h1>

    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-5">
        <input type="text" id="search-tickets" placeholder="Rechercher des tickets..." class="px-4 py-2 border rounded-lg w-full focus:outline-none focus:shadow-outline">
    </div>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div id="tickets-table" class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <tr class="border-b border-gray-200">
                                <th scope="col" class="py-3 px-4 text-left font-medium">Nom</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Prénom</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Numéro de Téléphone</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Message</th>
                                <th scope="col" class="py-3 px-4 text-left font-medium">Statut</th> 
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $ticket->user->firstname }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $ticket->user->lastname }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $ticket->user->phone_number }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $ticket->message }}</td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $ticket->statut }}</td> 
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                        <div class="flex items-center">
                                            <a href="{{ route('admin.contact.show', $ticket->id) }}" class="text-blue-500 hover:text-blue-700">Voir</a>
                                            <a href="{{ route('admin.contact.edit', $ticket->id) }}" class="text-yellow-500 hover:text-yellow-700 ml-4">Modifier</a>
                                            <form action="{{ route('admin.contact.destroy', $ticket->id) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce ticket?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div id="pagination-container" class="mt-4">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
th {
    background-color: #f9fafb;
}

th a {
    display: inline-flex;
    align-items: center;
    color: #4a5568;
    text-decoration: none;
    font-weight: 100;
    transition: color 0.3s ease;
}

th a:hover {
    color: #283e64;
}

th i.fas {
    color: #a0aec0;
}

th i.fas.fa-arrow-up,
th i.fas.fa-arrow-down {
    margin-left: 8px;
}

tr.border-b {
    border-bottom-width: 1px;
}
</style>
@endsection
