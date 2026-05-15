<script setup>
const props = defineProps({
    label: { type: String, default: null },
    isActive: { type: Boolean, default: false } // Controlled by parent
})

const emit = defineEmits(['toggle'])
</script>

<template>
    <div
        class="text-center min-w-15 p-2 select-none border-2 rounded-xl hover-pop"
        :class="{
            'border-secondary-text text-secondary-text' : !isActive,
            'border-tertiary-text text-tertiary-text bg-primary ' : isActive
        }"
        @click="emit('toggle')"
    >
        {{ label }}
    </div>
</template>
