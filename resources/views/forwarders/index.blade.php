<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-xl shadow-inner">
                    🔄
                </div>
                <div>
                    <h2 class="font-extrabold text-2xl text-gray-900 dark:text-white leading-tight font-heading">
                        إعادة توجيه البريد — Email Forwarders
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        تحويل الرسائل الواردة تلقائياً إلى بريدك الشخصي أو فريق العمل للدومين: <span class="font-bold text-emerald-600 dark:text-emerald-400 font-mono">{{ $activeDomain }}</span>
                    </p>
                </div>
            </div>

            <button @click="$dispatch('open-modal', 'create-forwarder-modal')" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-600 to-emerald-700 text-white hover:from-emerald-500 hover:to-emerald-600 shadow-md shadow-emerald-900/20 transition transform active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>إنشاء توجيه بريد جديد</span>
            </button>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6" x-data="{
        deleteAlias: null,
        copyText(text, msg = 'تم النسخ بنجاح!') {
            navigator.clipboard.writeText(text).then(() => {
                alert(msg);
            });
        },
        openDelete(alias) {
            this.deleteAlias = alias;
            $dispatch('open-modal', 'delete-forwarder-modal');
        }
    }">

        @if ($apiError)
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-800 dark:text-amber-200 text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                <span><strong>تنبيه النظام:</strong> {{ $apiError }}</span>
            </div>
        @endif

        <!-- Educational Info Card -->
        <div class="p-5 rounded-2xl bg-white dark:bg-[#1F2A24] border border-gray-200 dark:border-[#374A3C] shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-start gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold text-lg shrink-0">
                    💡
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">ما هي ميزة إعادة التوجيه (Email Forwarder)؟</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-relaxed max-w-3xl">
                        تتيح لك إنشاء عنوان بريد مهني رسمي (مثل: <code class="text-emerald-400 font-mono">contact@{{ $activeDomain }}</code> أو <code class="text-emerald-400 font-mono">jobs@{{ $activeDomain }}</code>) بحيث يتم تحويل كل الرسائل الواردة إليه فوراً وتلقائياً إلى إيميلك الشخصي على Gmail أو إيميلات موظفي الشركة، دون الحاجة لحجز مساحة تخزينية إضافية.
                    </p>
                </div>
            </div>

            <button @click="$dispatch('open-modal', 'create-forwarder-modal')" class="shrink-0 px-4 py-2 rounded-xl text-xs font-bold bg-[#374A3C] text-white hover:bg-[#506055] transition">
                + إضافة قاعدة توجيه
            </button>
        </div>

        <!-- Forwarders Table Card -->
        <div class="bg-white dark:bg-[#1F2A24] shadow-sm rounded-2xl overflow-hidden border border-gray-200 dark:border-[#374A3C]">
            <div class="p-6 border-b border-gray-100 dark:border-[#374A3C] flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <span>قواعد التوجيه المفعلة</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 font-semibold font-mono">
                            {{ count($forwarders) }} قاعدة
                        </span>
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        جميع العناوين البريدية التي تقوم بتحويل الرسائل إلى وجهات أخرى
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-right text-sm text-gray-600 dark:text-gray-300">
                    <thead class="bg-gray-50/70 dark:bg-[#141B17] text-[11px] uppercase tracking-wider font-bold text-gray-500 dark:text-[#A7B7A7] border-b border-gray-100 dark:border-[#374A3C]">
                        <tr>
                            <th scope="col" class="px-6 py-4">العنوان الأصلي (Alias)</th>
                            <th scope="col" class="px-6 py-4">الوجهة المحول إليها (Destination)</th>
                            <th scope="col" class="px-6 py-4">الحالة</th>
                            <th scope="col" class="px-6 py-4 text-left">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-[#374A3C]">
                        @forelse ($forwarders as $fwd)
                            <tr class="hover:bg-gray-50/60 dark:hover:bg-[#2B3B32]/40 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center font-bold text-xs">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 dark:text-white block font-mono text-sm" dir="ltr">{{ $fwd['alias'] . '@' . $activeDomain }}</span>
                                            <span class="text-[11px] text-gray-400">الاسم المستعار: {{ $fwd['alias'] }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-2 max-w-lg">
                                        @foreach ($fwd['destinations'] ?? [] as $dest)
                                            @if($dest === ':blackhole:')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-200 text-gray-800 dark:bg-[#141B17] dark:text-gray-300 border border-gray-300 dark:border-[#374A3C]">
                                                    🗑️ تجاهل صامت (:blackhole:)
                                                </span>
                                            @elseif($dest === ':fail:')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-500/10 text-rose-500 border border-rose-500/20">
                                                    ⛔ رفض مع إرسال خطأ للمرسل (:fail:)
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-600 dark:text-emerald-300 border border-emerald-500/20 font-mono" dir="ltr">
                                                    <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                                    <span>{{ $dest }}</span>
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        توجيه نشط
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <!-- Copy email address -->
                                        <button type="button" @click="copyText('{{ $fwd['alias'] . '@' . $activeDomain }}', 'تم نسخ عنوان البريد المستعار بنجاح!')" class="p-2 text-gray-400 hover:text-emerald-500 hover:bg-emerald-500/10 rounded-lg transition" title="نسخ عنوان البريد">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                        </button>

                                        <!-- Delete -->
                                        <button @click="openDelete('{{ $fwd['alias'] }}')" class="p-2 text-gray-400 hover:text-rose-500 hover:bg-rose-500/10 rounded-lg transition" title="حذف قاعدة التوجيه">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">
                                    <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-[#141B17] text-gray-400 flex items-center justify-center mx-auto mb-3 text-xl">
                                        🔄
                                    </div>
                                    <p class="font-bold text-gray-700 dark:text-gray-300">لا توجد قواعد إعادة توجيه منشأة بعد للدومين [{{ $activeDomain }}].</p>
                                    <button @click="$dispatch('open-modal', 'create-forwarder-modal')" class="mt-3 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 transition shadow-sm">
                                        + إنشاء أول قاعدة توجيه بريد
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal: Delete Forwarder Confirmation -->
            <x-modal name="delete-forwarder-modal" focusable>
                <form :action="'{{ url('forwarders') }}/' + deleteAlias" method="POST" class="p-6 text-right">
                    @csrf
                    @method('DELETE')

                    <h2 class="text-lg font-bold text-rose-600">
                        تأكيد حذف قاعدة إعادة التوجيه
                    </h2>

                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                        هل أنت متأكد من رغبتك في حذف قاعدة التوجيه للبريد <strong class="text-rose-600 font-mono" x-text="deleteAlias + '@' + '{{ $activeDomain }}'"></strong>؟
                        <br>
                        <span class="text-xs text-gray-400 mt-2 block">لن يتم تحويل الرسائل الواردة لهذا العنوان بعد الآن.</span>
                    </p>

                    <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-[#374A3C]">
                        <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">
                            إلغاء
                        </button>

                        <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-rose-600 text-white hover:bg-rose-500 shadow-md transition">
                            نعم، احذف التوجيه
                        </button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>

    <!-- Modal: Create Forwarder -->
    <x-modal name="create-forwarder-modal" :show="$errors->has('alias') || $errors->has('destinations')" focusable>
        <form action="{{ route('forwarders.store') }}" method="POST" class="p-6 text-right">
            @csrf

            <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                إنشاء توجيه بريد إلكتروني جديد
            </h2>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                تحويل أي رسالة تصل إلى هذا العنوان تلقائياً للوجهة المحددة تحت الدومين: <span class="font-bold text-emerald-500 font-mono">{{ $activeDomain }}</span>
            </p>

            <div class="mt-5 space-y-4">
                <!-- Alias -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">العنوان المستعار (البريد الذي سيستقبل الرسائل)</label>
                    <div class="flex rounded-xl shadow-sm overflow-hidden border border-gray-200 dark:border-[#374A3C]" dir="ltr">
                        <span class="inline-flex items-center px-4 bg-gray-100 dark:bg-[#141B17] text-gray-500 dark:text-gray-400 text-xs font-mono border-r border-gray-200 dark:border-[#374A3C]">
                            @ {{ $activeDomain }}
                        </span>
                        <input id="alias" name="alias" type="text" value="{{ old('alias') }}" required autofocus class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 border-0 font-mono" placeholder="contact, info, sales, support" />
                    </div>
                    <span class="text-[11px] text-gray-400 mt-1 block">مثال: اكتب contact ليكون البريد contact@{{ $activeDomain }}</span>
                    <x-input-error :messages="$errors->get('alias')" class="mt-1" />
                </div>

                <!-- Destinations -->
                <div>
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">البريد الإلكتروني للوجهة (المكان الذي ستتحول إليه الرسائل)</label>
                    <input id="destinations" name="destinations" type="text" value="{{ is_array(old('destinations')) ? implode(', ', old('destinations')) : old('destinations') }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-[#374A3C] bg-gray-50 dark:bg-[#141B17] text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-emerald-500 font-mono" placeholder="myname@gmail.com, team@company.com" dir="ltr" />
                    <p class="mt-1 text-[11px] text-gray-400">
                        يمكنك كتابة بريد واحد أو عدة إيميلات مفصولة بفاصلة ( , ). كما يُدعم الإسقاط الصامت عبر <code class="text-emerald-400 font-mono">:blackhole:</code>
                    </p>
                    <x-input-error :messages="$errors->get('destinations')" class="mt-1" />
                    <x-input-error :messages="$errors->get('destinations.*')" class="mt-1" />
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-gray-100 dark:border-[#374A3C]">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 rounded-xl text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#374A3C] transition">
                    إلغاء
                </button>

                <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-500 shadow-md transition">
                    حفظ وتفعيل التوجيه الآن
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
