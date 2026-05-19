<script setup>
const emit = defineEmits(['open']);
</script>

<template>
    <button
        type="button"
        class="fixed bottom-8 right-8 p-4 hover-pop bg-primary text-white rounded-2xl"
        @click="emit('open')"
        aria-label="Hochladen"
    >
        + Hochladen
    </button>
</template>

<style scoped>

</style>
