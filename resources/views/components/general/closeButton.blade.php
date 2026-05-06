@props([
    'border' => false,
    'borderColor' => '#555',
    'backgroundColor' => '#3F3F3F',
    'xColor' => '#AAA',
    'diameter' => 8
])

<div
    style="
        --size: {{ $diameter * 0.25 }}rem;
        background-color: {{ $backgroundColor }};
        color: {{ $xColor }};
        @if($border) border: 1px solid {{ $borderColor }}; @endif
    "
    class="w-[var(--size)] h-[var(--size)] cursor-pointer rounded-full flex items-center justify-center select-none"
    @click="$dispatch('close-overlay')"
>
    ✕
</div>
