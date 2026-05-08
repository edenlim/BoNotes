@props(['name'])

<template x-teleport="body">
    <div
        x-show="activeOverlay === '{{ $name }}'"
        x-transition.opacity
        @click="activeOverlay = null"
        @close-overlay.window="activeOverlay = null"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    >
        <div
            @click.stop {{-- .stop prevents event bubbling--}}
            class="bg-white rounded-lg shadow-xl max-w-[95vh] max-h-[90vh] overflow-y-auto"
        >
            {{ $slot }}
        </div>
    </div>
</template>
