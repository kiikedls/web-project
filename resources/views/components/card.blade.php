<div {{ $attributes->merge([
    'class'=>'bg-slate-800 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg'
])}}>
    <div class="p-6 text-slate-100 dark:text-gray-100">
        {{ $slot }}
    </div>
</div>