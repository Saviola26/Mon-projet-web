<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de l\'utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-lg font-semibold mb-4">Détails de l'utilisateur</h1>

                    {{-- Display success/error/warning messages --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    @if(session('warning'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('warning') }}</span>
                        </div>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h5 class="text-xl font-semibold mb-2 text-gray-900 dark:text-gray-100">{{ $user->name }}</h5>
                            <p class="text-gray-700 dark:text-gray-300 mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Rôle:</strong> {{ ucfirst($user->role) }}</p>
                        </div>
                    @else
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            Vous n'êtes pas autorisé à voir les détails de cet utilisateur.
                        </div>
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('seminaires.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Retour à la liste des séminaires
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
