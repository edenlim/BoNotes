<script setup>

</script>

<template>
<div role="search" class="flex items-center">
    <label for="searchbar" class="sr-only"></label>
    <input
        id="live-filter"
        type="search"
        v-model="query"
        class="w-full p-3 transition-transform duration-200 border-2 rounded-xl focus:ring-primary bg-secondary"
        placeholder="Titel der Notizen..."
        aria-label="Filter notes"
    />
</div>
</template>

<style scoped>

</style>
