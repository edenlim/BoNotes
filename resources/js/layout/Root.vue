<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import Cookies from 'js-cookie';
import Card from '../components/card/Card.vue';
import Navbar from "../components/navbar/Navbar.vue";
import Upload from "../components/upload/Upload.vue";
import Background from "../components/overlay/Background.vue";
import UploadOverlay from "../components/overlay/upload/UploadOverlay.vue";
// Import Preview here to manage it centrally
import Preview from '../components/overlay/preview/Preview.vue';

const responseData = ref([]);
const isLoading = ref(true);
const fetchError = ref(null);

const activeFilter = ref(['Alle']);
const rawSearchQuery = ref('');
const debouncedSearchQuery = ref('');
const showUploadOverlay = ref(false);

const session = ref(null);
const isLoggedIn = computed(() => !!session.value);

// --- SIMULATED ROUTING STATE ---
const currentNoteId = ref(null);
const showNoteOverlay = computed(() => currentNoteId.value !== null);

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

// Finds the reactive target item inside our state array
const activeNoteData = computed(() => {
    if (!currentNoteId.value) return null;
    return responseData.value.find(card => card.id == currentNoteId.value);
});

// Centralized voting mechanics for the active overlay instance
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

const refreshSession = () => {
    const cookieData = Cookies.get('current_login_session');
    session.value = cookieData ? JSON.parse(cookieData) : null;
};

onMounted(() => {
    loadMockData();
    refreshSession();
    parseUrlRoute();
    window.addEventListener('popstate', parseUrlRoute);
});

// Update-Handler für Bearbeitungen
const handleUpdateNote = (updatedNote) => {
    const index = responseData.value.findIndex(card => card.id === updatedNote.id);
    if (index !== -1) {
        responseData.value[index] = { ...responseData.value[index], ...updatedNote };
    }
};

// Delete-Handler für Löschungen
const handleDeleteNote = (noteId) => {
    responseData.value = responseData.value.filter(card => card.id !== noteId);
    navigateToNote(null); // Detailansicht schließen
};
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
            :session="session"
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
