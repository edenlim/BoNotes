@props(['name'])

<template x-teleport="body">
    <div 
        x-show="activeOverlay === '{{ $name }}'"
        x-transition.opacity
        {{-- Close when clicking the background --}}
        @click="activeOverlay = null"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    >
        <div 
            {{-- .stop prevents the background click logic from triggering when clicking inside the component --}}
            @click.stop 
            class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
        >
            {{ $slot }}
        </div>
    </div>
</template>