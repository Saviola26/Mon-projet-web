<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails du Séminaire') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-lg font-semibold mb-4">{{ $seminaire->theme }}</h1>

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

                    <div class="mb-4">
                        <p class="text-gray-700 dark:text-gray-300"><strong>Présentateur:</strong> {{ $seminaire->presentateur->name }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Date Proposée:</strong> {{ \Carbon\Carbon::parse($seminaire->date_proposee)->format('d/m/Y') }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Date Validée:</strong> {{ $seminaire->date_validee ? \Carbon\Carbon::parse($seminaire->date_validee)->format('d/m/Y') : 'N/A' }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Statut:</strong> <span class="font-bold">{{ ucfirst(str_replace('_', ' ', $seminaire->statut)) }}</span></p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Résumé:</strong> {{ $seminaire->resume ?? 'Aucun résumé fourni.' }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Nombre Maximum de Participants:</strong> {{ $seminaire->nombre_max_participants }}</p>
                        <p class="text-gray-700 dark:text-gray-300"><strong>Participants Actuels:</strong> {{ $seminaire->nombre_participants }}</p>

                        <div class="mt-4"> 
                            <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Liste des Participants :</h4>
                            @if($seminaire->participants && $seminaire->participants->count() > 0)
                                <ul class="list-disc list-inside text-gray-700 dark:text-gray-300">
                                    @foreach($seminaire->participants as $participant)
                                        <li>{{ $participant->name }}</li> 
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-gray-700 dark:text-gray-300">Aucun participant inscrit pour le moment.</p>
                            @endif
                        </div>

                        @if($seminaire->fichiers->isNotEmpty())
                            <p class="text-gray-700 dark:text-gray-300 mt-4"><strong>Fichier(s) de présentation:</strong></p>
                            <ul class="list-disc list-inside ml-4">
                                @foreach($seminaire->fichiers as $fichier)
                                    <li><a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">{{ $fichier->nom }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-700 dark:text-gray-300 mt-4">Aucun fichier de présentation disponible.</p>
                        @endif
                    </div>

                    <a href="{{ route('seminaires.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
