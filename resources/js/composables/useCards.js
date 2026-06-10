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

    return {
        responseData, isLoading, fetchError, loadData, activeNoteData,
        handleOverlayLike, handleOverlayDislike, handleUpdateNote, handleDeleteNote
    };
}
