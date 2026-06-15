<script setup>
import {ref, onMounted, onUnmounted} from 'vue';

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
import { useSearch } from '../composables/useSearch';
import { useInfiniteScroll } from "../composables/useInfiniteScroll.js";

// Initialize Logic
const { isLoggedIn,  checkSession, handleLogout } = useAuth();
const { currentNoteId, showNoteOverlay,  parseUrlRoute, navigateToNote } = useRouting();
const { activeNoteData, responseData, toggleLike, toggleDislike, handleUpdateNote, handleDeleteNote, isSingleCardLoading } = useCards(currentNoteId, navigateToNote);
const { activeFilter, rawSearchQuery, filteredCards } = useSearch(responseData);
const { isLoading, fetchError, hasMore, loadMoreCards, resetInfiniteScrollVariables } = useInfiniteScroll(responseData);

// Local UI State
const showUploadOverlay = ref(false);

const handleScroll = () => {
    if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 100) {
        if (!isLoading.value && hasMore.value) {
            loadMoreCards();
        }
    }
};

const handleLoginSuccess = async () => {
    resetInfiniteScrollVariables();
    await checkSession();
    await loadMoreCards();
};

const handleLogoutSuccess = async () => {
    resetInfiniteScrollVariables();
    handleLogout();
    await loadMoreCards();
};

// Lifecycle
onMounted(async () => {
    resetInfiniteScrollVariables();
    await checkSession();
    await loadMoreCards();
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

    <div v-if="isLoading && responseData.length === 0" class="p-4 text-white text-center">Laden...</div>
    <div v-else-if="fetchError && responseData.length === 0" class="p-4 text-red-500 text-center">Fehler: {{ fetchError }}</div>

    <div v-else>
        <div class="flex flex-wrap justify-center my-12 gap-[2rem] mx-16 md:mx-24 lg:mx-32 xl:mx-54">
            <Card
                v-for="(data, index) in filteredCards"
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
