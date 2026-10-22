<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Email Forwarders Management') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Route incoming emails to external addresses or discard rules for <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $activeDomain }}</span>
                </p>
            </div>

            <button @click="$dispatch('open-modal', 'create-forwarder-modal')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Forwarder
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        @if ($apiError)
            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span>{{ $apiError }}</span>
            </div>
        @endif

        <!-- Forwarders Table Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700/60" x-data="{
            deleteAlias: null,
            openDelete(alias) {
                this.deleteAlias = alias;
                $dispatch('open-modal', 'delete-forwarder-modal');
            }
        }">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Active Forwarding Rules</h3>
                    <p class="text-xs text-gray-500">Total forwarders: {{ count($forwarders) }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/80 dark:bg-gray-700/50 text-[11px] uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Forwarder Alias</th>
                            <th scope="col" class="px-6 py-3.5">Destination(s)</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse ($forwarders as $fwd)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-750/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold text-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white block">{{ $fwd['alias'] . '@' . $activeDomain }}</span>
                                            <span class="text-[11px] text-gray-400">Alias: {{ $fwd['alias'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5 max-w-md">
                                        @foreach ($fwd['destinations'] ?? [] as $dest)
                                            @if($dest === ':blackhole:')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    Silently Discard (:blackhole:)
                                                </span>
                                            @elseif($dest === ':fail:')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300">
                                                    Reject with Error (:fail:)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300 border border-indigo-200/50 dark:border-indigo-800/50">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    {{ $dest }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button @click="openDelete('{{ $fwd['alias'] }}')" class="p-1.5 text-gray-500 hover:text-rose-600 dark:text-gray-400 dark:hover:text-rose-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Delete Forwarder">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                                    <p class="font-medium">No forwarders configured for {{ $activeDomain }}.</p>
                                    <button @click="$dispatch('open-modal', 'create-forwarder-modal')" class="mt-2 text-indigo-600 dark:text-indigo-400 font-semibold text-xs hover:underline">
                                        Click here to create a forwarder
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal: Delete Forwarder Confirmation -->
            <x-modal name="delete-forwarder-modal" focusable>
                <form :action="'{{ url('forwarders') }}/' + deleteAlias" method="POST" class="p-6">
                    @csrf
                    @method('DELETE')

                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Delete Forwarder
                    </h2>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Are you sure you want to delete forwarding rule <strong class="text-rose-600" x-text="deleteAlias + '@' + '{{ $activeDomain }}'"></strong>?
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            Cancel
                        </x-secondary-button>

                        <x-danger-button>
                            Delete Rule
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>

    <!-- Modal: Create Forwarder -->
    <x-modal name="create-forwarder-modal" :show="$errors->has('alias') || $errors->has('destinations')" focusable>
        <form action="{{ route('forwarders.store') }}" method="POST" class="p-6">
            @csrf

            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                Create Email Forwarder
            </h2>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Forward emails sent to an alias under <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $activeDomain }}</span>
            </p>

            <div class="mt-4 space-y-4">
                <!-- Alias -->
                <div>
                    <x-input-label for="alias" value="Forwarder Alias" />
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <x-text-input id="alias" name="alias" type="text" value="{{ old('alias') }}" required autofocus class="block w-full rounded-none rounded-l-md" placeholder="e.g. contact, info, help" />
                        <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-500 text-xs">
                            @ {{ $activeDomain }}
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('alias')" class="mt-2" />
                </div>

                <!-- Destinations -->
                <div>
                    <x-input-label for="destinations" value="Destinations (comma-separated emails)" />
                    <x-text-input id="destinations" name="destinations" type="text" value="{{ is_array(old('destinations')) ? implode(', ', old('destinations')) : old('destinations') }}" required class="mt-1 block w-full" placeholder="user@gmail.com, team@company.com" />
                    <p class="mt-1 text-[11px] text-gray-500">
                        Enter one or more destination emails separated by commas. Special destinations supported: <code class="text-indigo-500 font-mono">:blackhole:</code> (silently discard) and <code class="text-rose-500 font-mono">:fail:</code> (reject).
                    </p>
                    <x-input-error :messages="$errors->get('destinations')" class="mt-2" />
                    <x-input-error :messages="$errors->get('destinations.*')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <x-primary-button>
                    Create Forwarder
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
