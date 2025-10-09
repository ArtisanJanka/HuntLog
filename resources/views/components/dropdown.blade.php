@props([
    'align' => 'right',     // left | right
    'width' => '64',        // 48|56|64
    'contentClasses' => '',
])

@php
    $alignment = $align === 'left' ? 'origin-top-left left-0' : 'origin-top-right right-0';
    $widthClass = match($width) {
        '48' => 'w-48',
        '56' => 'w-56',
        '64' => 'w-64',
        default => 'w-64',
    };
@endphp

<div x-data="{ open:false }" class="relative">
    {{-- Trigger --}}
    <div @click="open = !open"
         @keydown.escape.window="open = false"
         @click.outside="open = false">
        {{ $trigger }}
    </div>

    {{-- No-colored backdrop so nothing shows white behind --}}
    <div x-cloak
         x-show="open"
         x-transition.opacity
         class="fixed inset-0 z-40"
         @click="open = false"
         aria-hidden="true"></div>

    {{-- Panel (NO bg-white anywhere) --}}
    <div x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute mt-2 z-50 {{ $alignment }} {{ $widthClass }}"
         style="will-change: transform;">
        {{-- caret --}}
        <span class="pointer-events-none absolute -top-2 {{ $align === 'left' ? 'left-6' : 'right-6' }} h-4 w-4 rotate-45
                     bg-black/60 backdrop-blur-md border-l border-t border-white/10 rounded-sm"></span>

        {{-- your content wrapper (glassy) --}}
        <div class="rounded-2xl border border-white/10 bg-black/60 backdrop-blur-md shadow-2xl shadow-black/50 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
