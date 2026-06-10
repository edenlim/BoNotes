<script setup>
import { ref, onMounted, computed, watch, toRaw } from 'vue';
import Card from '../components/card/Card.vue';
import Navbar from "../components/navbar/Navbar.vue";
import Upload from "../components/upload/Upload.vue";
import Background from "../components/overlay/Background.vue";
import UploadOverlay from "../components/overlay/upload/UploadOverlay.vue";
import Preview from '../components/overlay/preview/Preview.vue';

// --- STATE ---
const responseData = ref([]);
const userData = ref(null); // Now strictly holds the logged-in user profile from Sanctum
const isLoggedIn = ref(false); // Controlled entirely by backend response
const isLoading = ref(true);
const fetchError = ref(null);

const activeFilter = ref(['Alle']);
const rawSearchQuery = ref('');
const debouncedSearchQuery = ref('');
const showUploadOverlay = ref(false);

const currentNoteId = ref(null);
const showNoteOverlay = computed(() => currentNoteId.value !== null);

// --- AUTHENTICATION ---
const checkSession = async () => {
    try {
        const response = await fetch('/api/user', {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            credentials: 'include'
        });

        if (response.ok) {
            isLoggedIn.value = true;
            userData.value = await response.json();
        } else {
            isLoggedIn.value = false;
            userData.value = null;
        }
    } catch (error) {
        console.error("Failed to verify session:", error);
        isLoggedIn.value = false;
        userData.value = null;
    }
};

const handleLogoutSuccess = () => {
    isLoggedIn.value = false;
    userData.value = null;
};

// --- ROUTING ---
const parseUrlRoute = () => {
    const path = window.location.pathname;
    const match = path.match(/^\/note\/(\d+)$/);
    if (match) {
        currentNoteId.value = parseInt(match[1], 10);
    } else {
        currentNoteId.value = null;
    }
};

const navigateToNote = (id) => {
    if (id) {
        window.history.pushState({}, '', `/note/${id}`);
    } else {
        window.history.pushState({}, '', '/');
    }
    parseUrlRoute();
};

// --- SEARCH & FILTER ---
let debounceTimer = null;
watch(rawSearchQuery, (e) => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        debouncedSearchQuery.value = e.trim().toLowerCase();
    }, 500);
});

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

// --- DATA FETCHING ---
const loadMockData = async () => {
    try {
        const response = await fetch('/api/cards', {
            method: 'GET',
            headers: {'Accept': 'application/json'},
            credentials: 'include'
        });

        if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

        responseData.value = await response.json();
        console.log("response: ", toRaw(responseData.value));
    } catch (error) {
        console.error("Failed to populate frontend state:", error);
        fetchError.value = error.message;
    } finally {
        isLoading.value = false;
    }
};

// --- DATA MUTATION ---
const activeNoteData = computed(() => {
    if (!currentNoteId.value) return null;
    return responseData.value.find(card => card.id == currentNoteId.value);
});

const handleOverlayLike = () => {
    const note = activeNoteData.value;
    if (!note) return;
    if (note.userVote === 'like') {
        note.noOfLikes--;
        note.userVote = null;
    } else {
        if (note.userVote === 'dislike') note.noOfDislikes--;
        note.noOfLikes++;
        note.userVote = 'like';
    }
};

const handleOverlayDislike = () => {
    const note = activeNoteData.value;
    if (!note) return;
    if (note.userVote === 'dislike') {
        note.noOfDislikes--;
        note.userVote = null;
    } else {
        if (note.userVote === 'like') note.noOfLikes--;
        note.noOfDislikes++;
        note.userVote = 'dislike';
    }
};

const handleUpdateNote = (updatedNote) => {
    const index = responseData.value.findIndex(card => card.id === updatedNote.id);
    if (index !== -1) {
        responseData.value[index] = { ...responseData.value[index], ...updatedNote };
    }
};

const handleDeleteNote = (noteId) => {
    responseData.value = responseData.value.filter(card => card.id !== noteId);
    navigateToNote(null);
};

// --- LIFECYCLE ---
onMounted(() => {
    checkSession();
    loadMockData();
    parseUrlRoute();
    window.addEventListener('popstate', parseUrlRoute);
});
</script>

<template>
    <Navbar
        v-model:activeFilter="activeFilter"
        v-model:searchQuery="rawSearchQuery"
        :isLoggedIn="isLoggedIn"
        @login-success="checkSession"
        @logout-success="handleLogoutSuccess"
    />

    <div v-if="isLoading" class="p-4 text-white text-center">Laden...</div>
    <div v-else-if="fetchError" class="p-4 text-red-500 text-center">Fehler: {{ fetchError }}</div>

    <div v-else>
        <div class="
            flex flex-wrap justify-center my-12 gap-[2rem]
            mx-16 md:mx-24 lg:mx-32 xl:mx-64
        ">
            <Card
                v-for="(data, index) in filteredCards"
                :key="index"
                :data="data"
                @click="navigateToNote(data.id)"
            />
        </div>
    </div>

    <Background :show="showNoteOverlay" @close="navigateToNote(null)">
        <Preview
            v-if="activeNoteData"
            :data="activeNoteData"
            :userData="userData"
            @close="navigateToNote(null)"
            @update:dislike="handleOverlayDislike"
            @update:like="handleOverlayLike"
            @delete-note="handleDeleteNote"
            @update-note="handleUpdateNote"
        />
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
