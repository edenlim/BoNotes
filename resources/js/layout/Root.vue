<script setup>
import { ref, onMounted, computed } from 'vue';
import Card from '../components/card/Card.vue';
import Navbar from "../components/navbar/Navbar.vue";
import Upload from "../components/upload/Upload.vue";

const responseData = ref([]);
const isLoading = ref(true);
const fetchError = ref(null);

// SSOT for filters
const activeFilter = ref(['Alle']);

const loadMockData = async () => {
    try {
        const response = await fetch('/mock/cards.json');
        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
        responseData.value = await response.json();
    } catch (error) {
        console.error("Failed to populate frontend state:", error);
        fetchError.value = error.message;
    } finally {
        isLoading.value = false;
    }
};

// Derived State: Dynamically filters the items based on active selection rules
const filteredCards = computed(() => {
    if (activeFilter.value.includes('Alle')) {
        return responseData.value;
    }

    return responseData.value.filter(card => {
        // Convert all card tags to lowercase to prevent casing mismatches
        const cardTags = card.tags.map(tag => tag.toLowerCase());

        // Return true if at least one active filter matches a tag on the card
        return activeFilter.value.some(filter =>
            cardTags.includes(filter.toLowerCase())
        );
    });
});

onMounted(() => {
    loadMockData();
});
</script>

<template>
    <Navbar v-model:activeFilter="activeFilter"/>

    <div v-if="isLoading" class="p-4 text-white text-center">Laden...</div>
    <div v-else-if="fetchError" class="p-4 text-red-500 text-center">Fehler: {{ fetchError }}</div>

    <div v-else class="mx-64 my-12 flex flex-wrap justify-center gap-[2rem]">
        <Card
            v-for="(data, index) in filteredCards"
            :key="index"
            :data="data"
        />

    </div>
    <Upload />

</template>

<style scoped>

</style>
