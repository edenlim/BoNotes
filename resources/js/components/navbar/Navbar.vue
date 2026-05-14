<script setup>
import Filters from "./Filters.vue";
import Account from "./Account.vue";
import Searchbar from "./Searchbar.vue";
</script>

<template>
    <nav class="grid grid-cols-12 bg-white w-full h-16 items-center px-8 gap-4 shadow-sm">
        <img
            class="h-[inherit] col-start-1 col-span-1 select-none transition-all duration-200 cursor-pointer hover:scale-110"
            :src="'/resources/images/bonotes.svg'"
            alt="BoNotes Logo"
        />
        <Searchbar class="col-span-4"/>
        <Filters class="col-span-6"/>
        <Account class="col-span-1"/>
    </nav>
</template>

<style scoped>

</style>
