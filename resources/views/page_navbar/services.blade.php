<x-app-layout>
    <div class="py-24">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('error'))
                        <div class="bg-red-500 text-white p-4 mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    {{ __('message.log') }} TEST
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
