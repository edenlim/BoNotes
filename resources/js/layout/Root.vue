<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import Card from '../components/card/Card.vue';
import Navbar from "../components/navbar/Navbar.vue";
import Upload from "../components/upload/Upload.vue";

const responseData = ref([]);
const isLoading = ref(true);
const fetchError = ref(null);

// SSOT for filters and raw inputs
const activeFilter = ref(['Alle']);
const rawSearchQuery = ref('');
const debouncedSearchQuery = ref('');

// Debounce logic: Wait 500ms after the last keystroke before updating the search filter
let debounceTimer = null;
watch(rawSearchQuery, (newValue) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        debouncedSearchQuery.value = newValue.trim().toLowerCase();
    }, 500); // 0.5 seconds timeout
});

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

// Derived State: Filters by both tags AND debounced search text input
const filteredCards = computed(() => {
    let cards = responseData.value;

    // 1. Process Tag Filtering
    if (!activeFilter.value.includes('Alle')) {
        const activeFiltersLower = activeFilter.value.map(filter => filter.toLowerCase());
        cards = cards.filter(card => {
            if (card.tags.length === 0) return false;
            return card.tags.every(cardTag =>
                activeFiltersLower.includes(cardTag.toLowerCase())
            );
        });
    }

    // 2. Process Debounced Text Search Filtering
    if (debouncedSearchQuery.value !== '') {
        cards = cards.filter(card =>
            card.title.toLowerCase().includes(debouncedSearchQuery.value)
        );
    }

    return cards;
});

onMounted(() => {
    loadMockData();
});
</script>

<template>
    <Navbar v-model:activeFilter="activeFilter" v-model:searchQuery="rawSearchQuery"/>

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
