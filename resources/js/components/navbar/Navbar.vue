<script setup>
import Filters from "./Filters.vue";
import Account from "./Account.vue";
import Searchbar from "./Searchbar.vue";

const activeFilter = defineModel('activeFilter', { type: Array, required: true });
const searchQuery = defineModel('searchQuery', { type: String, default: '' });
</script>

<template>
    <nav class="grid grid-cols-12 bg-white w-full h-16 items-center px-8 gap-4 shadow-sm">
        <img
            class="h-[inherit] col-start-1 col-span-1 select-none hover-pop"
            :src="'/resources/images/bonotes.svg'"
            alt="BoNotes Logo"
        />
        <Searchbar class="col-span-4" v-model:searchQuery="searchQuery"/>
        <Filters class="col-span-6" v-model:active-filter="activeFilter"/>
        <Account class="col-span-1"/>
    </nav>
</template>

<style scoped>

</style>
