<script setup>
import Filters from "./Filters.vue";
import Account from "./Account.vue";
import Searchbar from "./Searchbar.vue";

const activeFilter = defineModel('activeFilter', { type: Array, required: true });
const searchQuery = defineModel('searchQuery', { type: String, default: '' });
</script>

<template>
    <nav class="
        grid grid-cols-12 bg-white w-full items-center  py-2 gap-4 shadow-sm
        px-2 md:px-8
    ">
        <img
            class="
                w-auto max-w-none col-start-1 col-span-1 select-none hover-pop
                h-12 md:h-14 lg:h-16
            "
            :src="'/resources/images/bonotes.svg'"
            alt="BoNotes Logo"
        />
        <Searchbar class="col-span-7 md:col-span-8 lg:col-span-4" v-model:searchQuery="searchQuery"/>
        <Filters class="col-span-2 md:col-span-1 lg:col-span-6" v-model:active-filter="activeFilter"/>
        <Account class="col-span-2 md:col-span-2 lg:col-span-1"/>
    </nav>
</template>

<style scoped>

</style>
