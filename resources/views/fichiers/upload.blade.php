<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- MODIFICATION : Titre plus spécifique si tu veux --}}
            {{ __('Téléverser le fichier de présentation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                     <h1 class="text-lg font-semibold mb-1">
                        Téléverser la présentation pour : <strong>{{ $seminaire->theme }}</strong>
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Date du séminaire : {{ \Carbon\Carbon::parse($seminaire->date_validee)->format('d/m/Y') }}
                    </p>


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

                     <form action="{{ route('seminaires.handle_upload_presentation', $seminaire) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="fichier_presentation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fichier (PDF, PPT, PPTX)</label>
                            {{-- MODIFICATION : Le nom du champ 'name' et 'id' doit correspondre à ce que le contrôleur attend --}}
                            <input type="file" name="fichier_presentation" id="fichier_presentation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('fichier_presentation') border-red-500 @enderror" required>
                              @error('fichier_presentation')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                             Téléverser
                        </button>
                        <a href="{{ route('seminaires.show', $seminaire) }}" class="ml-4 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Annuler
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>