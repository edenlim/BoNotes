import { ref, computed } from 'vue';

export function useCards(currentNoteId, navigateToNote) {
    const responseData = ref([]);
    const isLoading = ref(true);
    const fetchError = ref(null);

    const loadData = async () => {
        try {
            const response = await fetch('/api/cards', {
                method: 'GET',
                headers: {'Accept': 'application/json'},
                credentials: 'include'
            });
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            responseData.value = await response.json();
        } catch (error) {
            console.error("Failed to populate frontend state:", error);
            fetchError.value = error.message;
        } finally {
            isLoading.value = false;
        }
    };

    const activeNoteData = computed(() => {
        if (!currentNoteId.value) return null;
        return responseData.value.find(card => card.id == currentNoteId.value);
    });

    const toggleLike = (card) => {
        if (!card) return;
        if (card.interaction_status === 'liked') {
            card.noOfLikes--;
            card.interaction_status = null;
        } else {
            if (card.interaction_status === 'disliked') card.noOfDislikes--;
            card.noOfLikes++;
            card.interaction_status = 'liked';
        }
    };

    const toggleDislike = (card) => {
        if (!card) return;
        if (card.interaction_status === 'disliked') {
            card.noOfDislikes--;
            card.interaction_status = null;
        } else {
            if (card.interaction_status === 'liked') card.noOfLikes--;
            card.noOfDislikes++;
            card.interaction_status = 'disliked';
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

    return {
        responseData, isLoading, fetchError, loadData, activeNoteData,
        toggleLike, toggleDislike, handleUpdateNote, handleDeleteNote
    };
}
