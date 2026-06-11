import { ref, computed } from 'vue';
import Cookies from 'js-cookie'; // Import js-cookie at the top of useCards.js


export function useCards(currentNoteId, navigateToNote) {
    const responseData = ref([]);
    const isLoading = ref(true);
    const fetchError = ref(null);

    const sendStateRequest = async (card, oldStatus, oldLikes, oldDislikes) => {
        try {
            const response = await fetch(`/api/cards/${card.id}/rate`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN') // Required for PUT requests in Sanctum stateful API
                },
                credentials: 'include',
                body: JSON.stringify({status: card.interaction_status})
            });
            if (!response.ok) {
                throw new Error(`Failed to rate card. Status: ${response.status}`);
            }

            const updatedData = await response.json();
            card.noOfLikes = updatedData.noOfLikes;
            card.noOfDislikes = updatedData.noOfDislikes;
            card.interaction_status = updatedData.interaction_status;
        } catch (error) {
            console.error("Rating failed, rolling back UI:", error);
            card.interaction_status = oldStatus;
            card.noOfLikes = oldLikes;
            card.noOfDislikes = oldDislikes;
            alert("Die Bewertung konnte nicht gespeichert werden.");
        }
    }

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

    const toggleLike = async (card) => {
        if (!card) return;

        const oldStatus = card.interaction_status;
        const oldLikes = card.noOfLikes;
        const oldDislikes = card.noOfDislikes;

        if (card.interaction_status === 'liked') {
            card.noOfLikes--;
            card.interaction_status = 'none';
        } else {
            if (card.interaction_status === 'disliked') card.noOfDislikes--;
            card.noOfLikes++;
            card.interaction_status = 'liked';
        }

        await sendStateRequest(card, oldStatus, oldLikes, oldDislikes);
    };

    const toggleDislike = async (card) => {
        if (!card) return;

        const oldStatus = card.interaction_status;
        const oldLikes = card.noOfLikes;
        const oldDislikes = card.noOfDislikes;

        if (card.interaction_status === 'disliked') {
            card.noOfDislikes--;
            card.interaction_status = 'none';
        } else {
            if (card.interaction_status === 'liked') card.noOfLikes--;
            card.noOfDislikes++;
            card.interaction_status = 'disliked';
        }

        await sendStateRequest(card, oldStatus, oldLikes, oldDislikes);
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
