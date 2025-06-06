<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un Séminaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Créer un Séminaire</h1>

                    {{-- Display success/error messages --}}
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

                    <form action="{{ route('seminaires.store') }}" method="POST">
                        @csrf
                        <div class="mb-4"> {{-- Utilisation de classes Tailwind pour l'espacement --}}
                            <label for="theme" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Thème</label>
                            <input type="text" name="theme" id="theme" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('theme') border-red-500 @enderror" value="{{ old('theme') }}" required>
                            @error('theme')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <div class="mb-4">
                                <label for="presentateur_nom_saisi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom du Présentateur (optionnel)</label>
                                <input type="text" name="presentateur_nom_saisi" id="presentateur_nom_saisi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('presentateur_nom_saisi') border-red-500 @enderror" value="{{ old('presentateur_nom_saisi') }}">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Si rempli, cherchera un utilisateur avec ce nom. Si laissé vide, vous (l'admin) serez le présentateur.</p>
                                @error('presentateur_nom_saisi')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="mb-4">
                            <label for="date_proposee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date Proposée</label>
                            <input type="date" name="date_proposee" id="date_proposee" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('date_proposee') border-red-500 @enderror" value="{{ old('date_proposee') }}" required>
                            @error('date_proposee')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-6"> {{-- mb-6 pour plus d'espace avant le bouton --}}
                            <label for="nombre_max_participants" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Maximum de Participants</label>
                            <input type="number" name="nombre_max_participants" id="nombre_max_participants" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('nombre_max_participants') border-red-500 @enderror" value="{{ old('nombre_max_participants') }}" required min="1">
                            @error('nombre_max_participants')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Envoyer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
