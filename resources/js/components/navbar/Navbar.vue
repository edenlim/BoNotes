<script setup>
import Filters from "./Filters.vue";
import Account from "./Account.vue";
import Searchbar from "./Searchbar.vue";

const props = defineProps({
    isLoggedIn: { type: Boolean, required: true }
});
const emit = defineEmits(['login-success', 'logout-success']);
const activeFilter = defineModel('activeFilter', { type: Array, required: true });
const searchQuery = defineModel('searchQuery', { type: String, default: '' });
</script>

<template>
    <nav class="
        flex items-center justify-between bg-white w-full py-2 shadow-sm sticky top-0 z-48
        px-2 md:px-8
        gap-2 md:gap-4
    ">
        <img
            class="
                w-auto max-w-none select-none hover-pop flex-shrink-0
                h-12 md:h-14 lg:h-16
            "
            :src="'/resources/images/bonotes.svg'"
            alt="BoNotes Logo"
        />

        <Searchbar class="flex-grow max-w-xl mx-2 md:mx-4" v-model:searchQuery="searchQuery"/>

        <div class="flex items-center gap-2 md:gap-4 flex-shrink-0">
            <Filters v-model:active-filter="activeFilter"/>
            <Account :isLoggedIn="props.isLoggedIn" @login-success="$emit('login-success')" @logout-success="$emit('logout-success')"/>
        </div>
    </nav>
</template>

<style scoped>

</style>
