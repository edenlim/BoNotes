import Cookies from 'js-cookie'; // Import js-cookie at the top of useCards.js
import { ref, computed } from 'vue';


export function useCards(currentNoteId, navigateToNote) {
    const responseData = ref([]);

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

    const handleUpdateNote = async (updatedNote) => {
        try {
            const response = await fetch(`/api/cards/${updatedNote.id}`, {
                method: 'PUT',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN')
                },
                credentials: 'include',
                body: JSON.stringify(updatedNote)
            });
            if (!response.ok) throw new Error(`Status: ${response.status}`);

            const savedCard = await response.json();
            const index = responseData.value.findIndex(card => card.id === savedCard.id);
            if (index !== -1) {
                responseData.value[index] = { ...responseData.value[index], ...savedCard };
            }
        } catch (error) {
            console.error("Speichern fehlgeschlagen:", error);
            alert("Die Änderungen konnten nicht gespeichert werden.");
        }
    };

    const handleDeleteNote = (noteId) => {
        responseData.value = responseData.value.filter(card => card.id !== noteId);
        navigateToNote(null);
    };

    return {
        responseData, activeNoteData,
        toggleLike, toggleDislike, handleUpdateNote, handleDeleteNote
    };
}
