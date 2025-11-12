<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Liste des Dépenses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(Auth::user()->role === 'EMPLOYEE')
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __("Vos Dépenses") }}</h3>
                        <div class="mb-4">
                            <a href="{{ route('expenses.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Créer une dépense') }}
                            </a>
                        </div>
                    @elseif(Auth::user()->role === 'MANAGER')
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __("Toutes les Dépenses") }}</h3>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Titre') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Montant') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Catégorie') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Statut') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('Date de dépense') }}
                                    </th>
                                    @if(Auth::user()->role === 'MANAGER')
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ __('Employé') }}
                                        </th>
                                    @endif
                                    <th scope="col" class="relative px-6 py-3">
                                        <span class="sr-only">{{ __('Actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($expenses as $expense)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $expense->title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $expense->amount }} {{ $expense->currency }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $expense->category }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $expense->status }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $expense->spent_at }}
                                        </td>
                                        @if(Auth::user()->role === 'MANAGER')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $expense->user->name }}
                                            </td>
                                        @endif
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Actions  --}}
                                            @if(Auth::user()->role === 'EMPLOYEE')
                                                {{-- Edit/Submit ] --}}
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">{{ __('Modifier') }}</a>
                                                {{-- Add submit button if status is DRAFT or REJECTED --}}
                                            @elseif(Auth::user()->role === 'MANAGER')
                                                {{-- Approve|Reject|Pay actions for manager --}}
                                                <a href="#" class="text-green-600 hover:text-green-900">{{ __('Approuver') }}</a>
                                                <a href="#" class="text-red-600 hover:text-red-900 ml-4">{{ __('Rejeter') }}</a>
                                                <a href="#" class="text-blue-600 hover:text-blue-900 ml-4">{{ __('Payer') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ Auth::user()->role === 'MANAGER' ? '7' : '6' }}" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ __('Aucune dépense trouvée.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>