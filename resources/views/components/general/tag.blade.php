{{-- views/components/general/tag.blade.php --}}
@props([
    'content' => '',
    'isToggleable' => false,
    'mutedClasses' => 'opacity-50 grayscale',
])

@php
    $colorStyle = match (strtolower($content)) {
        'wirtschaft' => 'text-[#633806] bg-[#FAEEDA]',
        'informatik' => 'text-[#155E75] bg-[#CFFAFE]',
        'mathe'      => 'text-[#3C3489] bg-[#EEEDFE]',
        default      => 'bg-gray-200 text-gray-800',
    };
@endphp

<div
    @if($isToggleable)
        x-data="{ active: true }"
        @click="active = !active"
        :class="active ? '{{ $colorStyle }}' : '{{ $mutedClasses }}'"
        {{ $attributes->merge(['class' => 'flex justify-center items-center rounded select-none transition-all text-center cursor-pointer']) }}
    @else
        {{ $attributes->merge(['class' => "flex justify-center items-center rounded select-none transition-all text-center $colorStyle"]) }}
    @endif
>
    {{ ucfirst($content) }}
</div>
