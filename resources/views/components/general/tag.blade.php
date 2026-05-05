@props([
    'content' => '',
    'activeClasses' => '',
    'mutedClasses' => 'opacity-50 grayscale', // Generic muted look
    'isToggleable' => false,
])

<div 
    @if($isToggleable)
        x-data="{ active: true }"
        @click="active = !active"
        :class="active ? '{{ $activeClasses }}' : '{{ $mutedClasses }}'"
        {{ $attributes->merge(['class' => 'rounded select-none transition-all cursor-pointer']) }}
    @else
        {{ $attributes->merge(['class' => 'rounded transition-all ' . $activeClasses]) }}
    @endif
>
    {{ ucfirst($content) }}
</div>