<!-- resources/views/partials/user-and-role.blade.php -->
<table class="min-w-full divide-y divide-gray-200">
    <thead>
        <tr>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ID
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Prénom
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nom
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Email
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Rôle
            </th>
            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($users as $user)
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->id }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->firstname }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->lastname }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->role }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <a href="{{ route('users.show', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Voir</a>
                <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Modifier</a>
                <button
                    class="ban-button {{ $user->banned ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900' }} ml-4"
                    data-id="{{ $user->id }}"
                    data-action="{{ $user->banned ? 'unban' : 'ban' }}">
                    {{ $user->banned ? 'Unban' : 'Ban' }}
                </button>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-user-form inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Supprimer</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{-- Pagination links --}}
{{ $users->links() }}

