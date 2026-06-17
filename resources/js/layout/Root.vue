<script setup>
import { ref, onMounted, onUnmounted, computed, watch } from 'vue';

// Components
import Card from '../components/card/Card.vue';
import Navbar from "../components/navbar/Navbar.vue";
import Upload from "../components/upload/Upload.vue";
import Background from "../components/overlay/Background.vue";
import UploadOverlay from "../components/overlay/upload/UploadOverlay.vue";
import Preview from '../components/overlay/preview/Preview.vue';

// Composables
import { useAuth } from '../composables/useAuth';
import { useRouting } from '../composables/useRouting';
import { useCards } from '../composables/useCards';
import { useInfiniteScroll } from "../composables/useInfiniteScroll.js";

// Local Search/Filter UI State
const activeFilter = ref(['Alle']);
const rawSearchQuery = ref('');
const debouncedSearchQuery = ref('');
let debounceTimer = null;

// Initialize Logic
const { isLoggedIn, session, checkSession, handleLogout } = useAuth();
const { currentNoteId, showNoteOverlay, parseUrlRoute, navigateToNote } = useRouting();
const { 
    unfilteredCards, 
    filteredCards, 
    isLoading, 
    fetchError, 
    hasMoreUnfiltered, 
    hasMoreFiltered, 
    loadMoreCards, 
    resetFilteredState, 
    resetAll 
} = useInfiniteScroll();

const isSearchActive = computed(() => {
    const hasSearch = rawSearchQuery.value.trim() !== '';
    const hasTags = activeFilter.value.length > 0 && !activeFilter.value.includes('Alle');
    return hasSearch || hasTags;
});

const activeCards = computed(() => {
    return isSearchActive.value ? filteredCards.value : unfilteredCards.value;
});

const hasMore = computed(() => {
    return isSearchActive.value ? hasMoreFiltered.value : hasMoreUnfiltered.value;
});

const { 
    activeNoteData, 
    toggleLike, 
    toggleDislike, 
    handleUpdateNote, 
    handleDeleteNote, 
    isSingleCardLoading 
} = useCards(currentNoteId, navigateToNote, unfilteredCards, filteredCards, activeCards);

// Local UI State
const showUploadOverlay = ref(false);

const handleScroll = () => {
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
        if (!isLoading.value && hasMore.value) {
            loadMoreCards(rawSearchQuery.value, activeFilter.value);
        }
    }
};

const handleLoginSuccess = async () => {
    resetAll();
    await checkSession();
    await loadMoreCards(rawSearchQuery.value, activeFilter.value);
};

const handleLogoutSuccess = async () => {
    resetAll();
    handleLogout();
    await loadMoreCards(rawSearchQuery.value, activeFilter.value);
};

// Debounce search query
watch(rawSearchQuery, (newValue) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        debouncedSearchQuery.value = newValue.trim().toLowerCase();
    }, 500);
});

// Watch debounced query and filters to fetch search results
watch([debouncedSearchQuery, activeFilter], async () => {
    resetFilteredState();
    if (isSearchActive.value) {
        await loadMoreCards(rawSearchQuery.value, activeFilter.value);
    }
});

// Lifecycle
onMounted(async () => {
    resetAll();
    await checkSession();
    await loadMoreCards(rawSearchQuery.value, activeFilter.value);
    parseUrlRoute();
    window.addEventListener('popstate', parseUrlRoute);
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

</script>

<template>
    <Navbar
        v-model:activeFilter="activeFilter"
        v-model:searchQuery="rawSearchQuery"
        :isLoggedIn="isLoggedIn"
        @login-success="handleLoginSuccess"
        @logout-success="handleLogoutSuccess"
    />

    <div v-if="isLoading && activeCards.length === 0" class="p-4 text-white text-center">Laden...</div>
    <div v-else-if="fetchError && activeCards.length === 0" class="p-4 text-red-500 text-center">Fehler: {{ fetchError }}</div>

    <div v-else>
        <div class="flex flex-wrap justify-center my-12 gap-[2rem] mx-16 md:mx-24 lg:mx-32 xl:mx-54">
            <Card
                v-for="(data, index) in activeCards"
                :key="index"
                :data="data"
                @click="navigateToNote(data.id)"
                @toggle-like="toggleLike(data)"
                @toggle-dislike="toggleDislike(data)"
            />
        </div>
        <div v-if="isLoading" class="p-4 text-white text-center">Lade weitere Notizen...</div>
    </div>

    <Background :show="showNoteOverlay" @close="navigateToNote(null)">
        <Preview
            v-if="activeNoteData"
            :data="activeNoteData"
            :session="session"
            @close="navigateToNote(null)"
            @update:like="toggleLike(activeNoteData)"
            @update:dislike="toggleDislike(activeNoteData)"
            @delete-note="handleDeleteNote"
            @update-note="handleUpdateNote"
        />
        <div v-else-if="isSingleCardLoading" class="p-8 text-white text-center min-w-[300px]">
            Laden...
        </div>
        <div v-else class="p-8 text-red-500 text-center min-w-[300px]">
            Notiz nicht gefunden.
        </div>
    </Background>

    <div v-if="isLoggedIn">
        <Upload @open="showUploadOverlay = true" />
        <Background :show="showUploadOverlay" @close="showUploadOverlay = false">
            <UploadOverlay @close="showUploadOverlay = false" />
        </Background>
    </div>
</template>
