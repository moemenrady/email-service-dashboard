<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Email Accounts Management') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Manage mailboxes and storage quotas for domain <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $activeDomain }}</span>
                </p>
            </div>

            <button @click="$dispatch('open-modal', 'create-email-modal')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Email Account
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

        <!-- Email Accounts Table Card -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700/60" x-data="{
            editUser: null,
            editUsername: '',
            editQuota: 1024,
            editLimit: 9600,
            deleteUser: null,
            openEdit(email) {
                this.editUser = email.username;
                this.editUsername = email.username;
                this.editQuota = email.quota;
                this.editLimit = email.limit;
                $dispatch('open-modal', 'edit-email-modal');
            },
            openDelete(username) {
                this.deleteUser = username;
                $dispatch('open-modal', 'delete-email-modal');
            }
        }">
            <div class="p-5 border-b border-gray-100 dark:border-gray-700/60 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Active Mailboxes</h3>
                    <p class="text-xs text-gray-500">Total configured: {{ count($emails) }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/80 dark:bg-gray-700/50 text-[11px] uppercase tracking-wider font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3.5">Email Address</th>
                            <th scope="col" class="px-6 py-3.5">Storage Quota</th>
                            <th scope="col" class="px-6 py-3.5">Daily Limit / Sent</th>
                            <th scope="col" class="px-6 py-3.5">Status</th>
                            <th scope="col" class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @forelse ($emails as $email)
                            @php
                                $quotaMb = (int) ($email['quota'] ?? 1024);
                                $usageMb = (float) ($email['usage'] ?? 0);
                                $pct = $quotaMb > 0 ? min(100, round(($usageMb / $quotaMb) * 100)) : 0;
                            @endphp
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-750/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-xs uppercase">
                                            {{ substr($email['username'], 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white block">{{ $email['username'] . '@' . $activeDomain }}</span>
                                            <span class="text-[11px] text-gray-400">User: {{ $email['username'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="w-48">
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $usageMb }} MB</span>
                                            <span class="text-gray-400">{{ $quotaMb === 0 ? 'Unlimited' : $quotaMb . ' MB' }}</span>
                                        </div>
                                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full overflow-hidden">
                                            <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $pct }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $email['sent'] ?? 0 }}</span>
                                    <span class="text-gray-400 text-xs">/ {{ $email['limit'] ?? 9600 }} msgs/day</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ !empty($email['suspended']) ? 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' }}">
                                        {{ !empty($email['suspended']) ? 'Suspended' : 'Active' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <button @click="openEdit({{ json_encode($email) }})" class="p-1.5 text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Edit Mailbox">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <button @click="openDelete('{{ $email['username'] }}')" class="p-1.5 text-gray-500 hover:text-rose-600 dark:text-gray-400 dark:hover:text-rose-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Delete Mailbox">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <p class="font-medium">No mailboxes found on {{ $activeDomain }}.</p>
                                    <button @click="$dispatch('open-modal', 'create-email-modal')" class="mt-2 text-indigo-600 dark:text-indigo-400 font-semibold text-xs hover:underline">
                                        Click here to create the first email account
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal: Edit Email -->
            <x-modal name="edit-email-modal" focusable>
                <form :action="'{{ url('emails') }}/' + editUser" method="POST" class="p-6">
                    @csrf
                    @method('PATCH')

                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Edit Mailbox: <span class="text-indigo-600 dark:text-indigo-400" x-text="editUsername + '@' + '{{ $activeDomain }}'"></span>
                    </h2>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Leave the password field blank to keep the current password.
                    </p>

                    <div class="mt-4 space-y-4">
                        <div>
                            <x-input-label for="edit_password" value="New Password (8–72 characters)" />
                            <x-text-input id="edit_password" name="password" type="password" class="mt-1 block w-full" placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="edit_quota" value="Storage Quota (MB, 0 = unlimited)" />
                            <x-text-input id="edit_quota" name="quota" type="number" min="0" x-model="editQuota" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('quota')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="edit_limit" value="Daily Send Limit (Max 9600)" />
                            <x-text-input id="edit_limit" name="limit" type="number" min="1" max="9600" x-model="editLimit" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('limit')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            Cancel
                        </x-secondary-button>

                        <x-primary-button>
                            Update Mailbox
                        </x-primary-button>
                    </div>
                </form>
            </x-modal>

            <!-- Modal: Delete Email Confirmation -->
            <x-modal name="delete-email-modal" focusable>
                <form :action="'{{ url('emails') }}/' + deleteUser" method="POST" class="p-6">
                    @csrf
                    @method('DELETE')

                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Delete Mailbox
                    </h2>

                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Are you sure you want to permanently delete mailbox <strong class="text-rose-600" x-text="deleteUser + '@' + '{{ $activeDomain }}'"></strong>?
                        All messages and stored emails will be permanently removed.
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            Cancel
                        </x-secondary-button>

                        <x-danger-button>
                            Confirm Delete
                        </x-danger-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>

    <!-- Modal: Create Email Account -->
    <x-modal name="create-email-modal" :show="$errors->has('username') || $errors->has('password')" focusable>
        <form action="{{ route('emails.store') }}" method="POST" class="p-6" x-data="{
            pass: '',
            generatePassword() {
                const chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789!@#$%&*';
                let p = '';
                for (let i = 0; i < 16; i++) {
                    p += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                this.pass = p;
                $refs.passInput.value = p;
            }
        }">
            @csrf

            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                Create New Mailbox
            </h2>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Create a secure mailbox under <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $activeDomain }}</span>
            </p>

            <div class="mt-4 space-y-4">
                <!-- Username -->
                <div>
                    <x-input-label for="username" value="Mailbox Username" />
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <x-text-input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus class="block w-full rounded-none rounded-l-md" placeholder="e.g. sales, john.doe" />
                        <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 text-gray-500 text-xs">
                            @ {{ $activeDomain }}
                        </span>
                    </div>
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Password with generator -->
                <div>
                    <div class="flex justify-between items-center">
                        <x-input-label for="password" value="Password (8–72 characters)" />
                        <button type="button" @click="generatePassword()" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                            Generate Strong Password
                        </button>
                    </div>
                    <x-text-input id="password" x-ref="passInput" name="password" type="text" required class="mt-1 block w-full" placeholder="Enter secure password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Storage Quota -->
                <div>
                    <x-input-label for="quota" value="Storage Quota in MB (0 = Unlimited, Default = 1024 MB)" />
                    <x-text-input id="quota" name="quota" type="number" min="0" value="{{ old('quota', 1024) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('quota')" class="mt-2" />
                </div>

                <!-- Daily Limit -->
                <div>
                    <x-input-label for="limit" value="Daily Send Limit (Default & Maximum = 9600)" />
                    <x-text-input id="limit" name="limit" type="number" min="1" max="9600" value="{{ old('limit', 9600) }}" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->get('limit')" class="mt-2" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Cancel
                </x-secondary-button>

                <x-primary-button>
                    Create Mailbox
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
