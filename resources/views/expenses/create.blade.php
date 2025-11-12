<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Créer une Dépense') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('expensesApi.store') }}">
                        @csrf

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Titre')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Amount -->
                        <div class="mt-4">
                            <x-input-label for="amount" :value="__('Montant')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="number" step="0.01" name="amount" :value="old('amount')" required />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Currency -->
                        <div class="mt-4">
                            <x-input-label for="currency" :value="__('Devise')" />
                            <x-text-input id="currency" class="block mt-1 w-full" type="text" name="currency" :value="old('currency', 'EUR')" required />
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>

                        <!-- Spent At -->
                        <div class="mt-4">
                            <x-input-label for="spent_at" :value="__('Date de dépense')" />
                            <x-text-input id="spent_at" class="block mt-1 w-full" type="date" name="spent_at" :value="old('spent_at')" required />
                            <x-input-error :messages="$errors->get('spent_at')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category" :value="__('Catégorie')" />
                            <select id="category" name="category" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="MEAL">{{ __('Repas') }}</option>
                                <option value="TRAVEL">{{ __('Voyage') }}</option>
                                <option value="HOTEL">{{ __('Hôtel') }}</option>
                                <option value="OTHER">{{ __('Autre') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('category')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Créer') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>