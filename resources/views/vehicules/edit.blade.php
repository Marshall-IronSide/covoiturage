<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            ✏️ {{ __('Modifier mon véhicule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    {{-- Message d'erreur --}}
                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Formulaire --}}
                    <form method="POST" action="{{ route('vehicule.update', $vehicule) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- Photo actuelle --}}
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                📷 Photo actuelle
                            </label>
                            <img src="{{ asset('storage/' . $vehicule->photo) }}"
                                 alt="Véhicule actuel"
                                 class="w-full max-w-md h-48 object-cover rounded-lg shadow border-2 border-gray-200">
                        </div>

                        {{-- Nouvelle photo --}}
                        <div class="mb-4">
                            <label for="photo" class="block text-sm font-medium text-gray-700">
                                📷 Nouvelle photo (optionnel)
                            </label>
                            <input type="file" 
                                   name="photo" 
                                   id="photo" 
                                   accept="image/*"
                                   onchange="previewImage(event)"
                                   class="mt-1 block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-indigo-50 file:text-indigo-700
                                          hover:file:bg-indigo-100 cursor-pointer">
                            <p class="mt-1 text-sm text-gray-500">
                                Laissez vide pour conserver la photo actuelle. Formats : JPG, PNG, GIF (max 2MB)
                            </p>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- Prévisualisation de la nouvelle image --}}
                            <div id="imagePreview" class="mt-3 hidden">
                                <p class="text-sm font-medium text-gray-700 mb-2">Aperçu de la nouvelle photo :</p>
                                <img id="preview" src="" alt="Aperçu" class="w-full max-w-md h-48 object-cover rounded-lg shadow">
                            </div>
                        </div>

                        {{-- Numéro de plaque --}}
                        <div class="mb-4">
                            <label for="numero_plaque" class="block text-sm font-medium text-gray-700">
                                🔢 Numéro de plaque <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   name="numero_plaque" 
                                   id="numero_plaque" 
                                   value="{{ old('numero_plaque', $vehicule->numero_plaque) }}" 
                                   required
                                   placeholder="Ex: AB-123-CD"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('numero_plaque')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                📝 Description du véhicule <span class="text-red-600">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="4" 
                                      required
                                      placeholder="Ex: Toyota Corolla 2020, couleur blanche, 5 places, climatisation"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $vehicule->description) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                Incluez la marque, le modèle, la couleur et toute particularité utile
                            </p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Boutons --}}
                        <div class="flex gap-4">
                            <button type="submit" 
                                    class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-semibold shadow">
                                ✅ Enregistrer les modifications
                            </button>
                            <a href="{{ route('vehicule.show', $vehicule) }}" 
                               class="bg-gray-600 text-white px-6 py-3 rounded-md hover:bg-gray-700 font-semibold shadow">
                                ❌ Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script pour la prévisualisation de l'image --}}
    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>