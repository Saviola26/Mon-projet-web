<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Rôle --}}
                        <div class="row mb-3">
                            <label for="role" class="col-md-4 col-form-label text-md-end">Rôle</label>
                            <div class="col-md-6">
                            <select id="role" name="role" class="form-select" required>
                                <option value="">-- Choisir un rôle --</option>
                                <option value="etudiant" >Étudiant</option>
                                <option value="presentateur" >Présentateur</option>
                                <option value="admin" >Administrateur</option>
                            </select>
                                @error('role')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror

                            </div>
                        </div>

                        {{-- Champs supplémentaires pour admin --}}
                        <div id="admin-fields" style="display: none;">
                            <div class="row mb-3">
                                <label for="admin_name" class="col-md-4 col-form-label text-md-end">Nom d'administrateur</label>
                                <div class="col-md-6">
                                    <input id="admin_name" type="text" class="form-control" name="admin_name">
                                    @error('admin_name')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror

                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="admin_code" class="col-md-4 col-form-label text-md-end">Code secret</label>
                                <div class="col-md-6">
                                    <input id="admin_code" type="password" class="form-control" name="admin_code">
                                    @error('admin_code')
                                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                    @enderror

                                </div>
                            </div>
                        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const adminFieldsDiv = document.getElementById('admin-fields');

            function toggleAdminFields() {
                if (roleSelect.value === 'admin') {
                    adminFieldsDiv.style.display = 'block';
                    // Rendre les champs requis si nécessaire (à gérer côté serveur aussi)
                    document.getElementById('admin_name').setAttribute('required', true);
                    document.getElementById('admin_code').setAttribute('required', true);
                } else {
                    adminFieldsDiv.style.display = 'none';
                    // Supprimer l'attribut required quand les champs sont cachés
                    document.getElementById('admin_name').removeAttribute('required');
                    document.getElementById('admin_code').removeAttribute('required');
                    // Vider les champs pour éviter l'envoi de données indésirables
                    document.getElementById('admin_name').value = '';
                    document.getElementById('admin_code').value = '';
                }
            }

            // Appeler la fonction au chargement de la page pour gérer l'état initial (si old('role') est 'admin')
            toggleAdminFields();

            // Écouter les changements sur le sélecteur de rôle
            roleSelect.addEventListener('change', toggleAdminFields);
        });
    </script>
    
</x-guest-layout>
