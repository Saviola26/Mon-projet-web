<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord Présentateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1 class="text-lg font-semibold mb-4">Mes Demandes de Séminaires</h1>

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

                    @if($seminaires->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Vous n'avez pas encore soumis de demandes de séminaires.</p>
                        <a href="{{ route('seminaires.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4">
                            Créer une nouvelle demande de séminaire
                        </a>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Thème
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
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($seminaire->date_proposee)->format('d/m/Y') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $seminaire->date_validee ? \Carbon\Carbon::parse($seminaire->date_validee)->format('d/m/Y') : 'N/A' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $seminaire->statut === 'valide' ? 'bg-green-100 text-green-800' : ($seminaire->statut === 'en_attente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $seminaire->statut)) }}
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
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('seminaires.show', $seminaire) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-2">Voir</a>
                                                @if($seminaire->statut === 'en_attente')
                                                    <a href="{{ route('seminaires.edit', $seminaire) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-2">Modifier</a>
                                                    <form action="{{ route('seminaires.destroy', $seminaire) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce séminaire ?')">Supprimer</button>
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


                                                @if (
                                                    (Auth::check() && (Auth::id() === $seminaire->user_id || Auth::user()->role === 'admin')) && 
                                                    ($seminaire->date_validee) &&
                                                    (\Carbon\Carbon::parse($seminaire->date_validee)->isPast())
                                                )
                                                    <a href="{{ route('seminaires.show_upload_presentation_form', $seminaire) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                                        Téléverser Présentation
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
