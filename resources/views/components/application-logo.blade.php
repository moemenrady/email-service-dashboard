<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    <div class="relative flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-blue-600 to-cyan-500 shadow-md shadow-indigo-500/20 text-white font-bold text-lg">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
        <span class="absolute -bottom-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-white dark:border-gray-900"></span>
        </span>
    </div>
    <div class="flex flex-col text-left">
        <span class="font-extrabold text-base tracking-tight text-gray-900 dark:text-white leading-tight">
            Moemen <span class="text-indigo-600 dark:text-indigo-400">Workspace</span>
        </span>
        <span class="text-[10px] font-medium text-gray-500 dark:text-gray-400 tracking-wider">
            فريق مؤمن للتطوير البرمجي
        </span>
    </div>
</div>
