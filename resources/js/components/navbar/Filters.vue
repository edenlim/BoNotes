<script setup>
import Filter from "../../templates/Filter.vue";
const labels = ['Alle', 'Mathe', 'Informatik', 'Wirtschaft']
</script>

<template>
    <div class="flex flex-row gap-4 items-center justify-center h-full">
        <Filter
            v-for="label in labels"
            :label="label"
        />
    </div>
</template>

<style scoped>

</style>
