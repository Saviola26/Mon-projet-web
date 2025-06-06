<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des Séminaires') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-2xl font-bold mb-6">Liste des Séminaires</h1>

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

                    @if (Auth::user()->role !== 'etudiant')
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('seminaires.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150 w-full sm:w-auto justify-center">
                                Créer un Séminaire
                            </a>
                            <a href="{{ route('seminaires.mesDemandes') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition ease-in-out duration-150 w-full sm:w-auto justify-center">
                                Mes Soumissions
                            </a>
                        </div>
                    @endif

                    <div class="overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Thème
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Présentateur
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date Proposée
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date Validée
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Statut
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Résumé
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Fichier(s)
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($seminaires as $seminaire)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $seminaire->theme }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $seminaire->presentateur->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $seminaire->date_proposee }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $seminaire->date_validee }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $seminaire->statut === 'valide' ? 'bg-green-100 text-green-800' : ($seminaire->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                {{ $seminaire->statut }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 max-w-xs overflow-hidden text-ellipsis">{{ $seminaire->resume }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @forelse($seminaire->fichiers as $fichier)
                                                <a href="{{ asset('storage/' . $fichier->chemin) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">{{ $fichier->nom }}</a><br>
                                            @empty
                                                <span class="text-gray-500 dark:text-gray-400">Aucun fichier</span>
                                            @endforelse
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-y-2 sm:space-y-0 sm:space-x-2 flex flex-col sm:flex-row items-start sm:items-center">
                                            <a href="{{ route('seminaires.show', $seminaire) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Voir</a>
                                            
                                            @if(auth()->user()->id === $seminaire->user_id || auth()->user()->role === 'admin')
                                                <a href="{{ route('seminaires.edit', $seminaire) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200">Modifier</a>
                                                <form action="{{ route('seminaires.destroy', $seminaire) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce séminaire ?')">Supprimer</button>
                                                </form>
                                            @endif
                                            
                                            @if (auth()->user()->role === 'admin' && $seminaire->statut === 'en_attente')
                                                <form action="{{ route('seminaires.valider', $seminaire->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <input type="date" name="date_validee" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm w-full sm:w-auto mt-2 sm:mt-0">
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mt-2 sm:mt-0">Valider</button>
                                                </form>
                                            @endif
                                            
                                            @php
                                                $seminaireDateValidee = \Carbon\Carbon::parse($seminaire->date_validee);
                                                $today = \Carbon\Carbon::now();
                                                $tenDaysBeforeSeminar = $seminaireDateValidee->copy()->subDays(10);
                                            @endphp

                                            @if (auth()->user()->id === $seminaire->user_id && 
                                                 $seminaire->statut === 'valide' && 
                                                 empty($seminaire->resume) &&
                                                 $today->greaterThanOrEqualTo($tenDaysBeforeSeminar) &&
                                                 $today->lessThan($seminaireDateValidee))
                                                <form action="{{ route('seminaires.resume', $seminaire->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <textarea name="resume" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm text-sm w-full sm:w-auto mt-2 sm:mt-0" placeholder="Entrez le résumé"></textarea>
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 mt-2 sm:mt-0">Envoyer Résumé</button>
                                                </form>
                                            @endif
                                            
                                            @if (auth()->user()->role === 'admin')
                                                <form action="{{ route('seminaires.publier', $seminaire->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">Publier</button>
                                                </form>
                                            @endif

                                            @if (Auth::user()->role === 'admin' && $seminaire->statut === 'valide' && \Carbon\Carbon::parse($seminaire->date_validee)->isPast())
                                                <form action="{{ route('seminaires.terminer', $seminaire) }}" method="POST" class="d-inline">
                                                    @csrf 
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mt-2 sm:mt-0">
                                                        Terminer
                                                    </button>
                                                </form>

                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
