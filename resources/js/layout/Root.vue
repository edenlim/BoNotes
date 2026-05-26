<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import Cookies from 'js-cookie';
import Card from '../components/card/Card.vue';
import Navbar from "../components/navbar/Navbar.vue";
import Upload from "../components/upload/Upload.vue";
import Background from "../components/overlay/Background.vue";
import UploadOverlay from "../components/overlay/upload/UploadOverlay.vue";

const responseData = ref([]);
const isLoading = ref(true);
const fetchError = ref(null);

const activeFilter = ref(['Alle']);
const rawSearchQuery = ref('');
const debouncedSearchQuery = ref('');
const showUploadOverlay = ref(false);

const session = ref(null);
const isLoggedIn = computed(() => !!session.value);
/*
 * Set timeout to only run 0.5 seconds after user stopped typing into the searchbar
 * - Whenever user is typing, it removes any timeout debounceTimer and creates a new timeout debounceTimer
 * - The timeout will execute updating debouncedSearchQuery = the value from searchbar after 0.5sec
 * - If the timeout is still counting down and the user is still typing, it deletes and creates (resets) a new 0.5sec timeout
 *     - This allows debouncing
 */

let debounceTimer = null;
watch(rawSearchQuery, (e) => {
    // Note that e === rawSearchQuery.value
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        debouncedSearchQuery.value = e.trim().toLowerCase();
    }, 500);
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

const filteredCards = computed(() => {
    let cards = responseData.value;

    if (!activeFilter.value.includes('Alle')) {
        const activeFiltersLower = activeFilter.value.map(filter => filter.toLowerCase());
        cards = cards.filter(card => {
            if (card.tags.length === 0) return false;
            return card.tags.every(cardTag =>
                activeFiltersLower.includes(cardTag.toLowerCase())
            );
        });
    }

    if (debouncedSearchQuery.value !== '') {
        cards = cards.filter(card =>
            card.title.toLowerCase().includes(debouncedSearchQuery.value)
        );
    }

    return cards;
});

const refreshSession = () => {
    const cookieData = Cookies.get('current_login_session');
    session.value = cookieData ? JSON.parse(cookieData) : null;
};

onMounted(() => {
    loadMockData();
    refreshSession();
});
</script>
<template>
    <Navbar
        v-model:activeFilter="activeFilter"
        v-model:searchQuery="rawSearchQuery"
        :isLoggedIn="isLoggedIn"
        @login-success="refreshSession"
        @logout-success="refreshSession"
    />

    <div v-if="isLoading" class="p-4 text-white text-center">Laden...</div>
    <div v-else-if="fetchError" class="p-4 text-red-500 text-center">Fehler: {{ fetchError }}</div>

    <div v-else
        class="
            flex flex-wrap justify-center my-12 gap-[2rem]
            mx-16 md:mx-24 lg:mx-32 xl:mx-64
    ">
        <Card
            v-for="(data, index) in filteredCards"
            :key="index"
            :data="data"
        />
    </div>

    <div v-if="isLoggedIn">
        <Upload @open="showUploadOverlay = true" />
        <Background :show="showUploadOverlay" @close="showUploadOverlay = false">
            <UploadOverlay @close="showUploadOverlay = false" />
        </Background>
    </div>

</template>
