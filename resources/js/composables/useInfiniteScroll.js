import {ref} from "vue";

export function useInfiniteScroll(responseData) {
    const lastIndex = ref(0);
    const isLoading = ref(true);
    const fetchError = ref(null);
    const hasMore = ref(true);

    const loadMoreCards = async () => {
        if (!hasMore.value) return;
        isLoading.value = true;
        try {
            const response = await fetch(`/api/cards/${lastIndex.value}`, {
                method: 'GET',
                headers: {'Accept': 'application/json'},
                credentials: 'include'
            });
            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const newCards = await response.json();

            if (newCards.length < 20) {
                hasMore.value = false;
            }

            responseData.value = [...responseData.value, ...newCards];
        } catch (error) {
            console.error("Failed to populate frontend state:", error);
            fetchError.value = error.message;
        } finally {
            isLoading.value = false;
            lastIndex.value += 20;
        }
    }

    const resetInfiniteScrollVariables = () => {
        responseData.value = [];
        lastIndex.value = 0;
        hasMore.value = true;
        fetchError.value = null;
    }

    return {
        lastIndex, isLoading, fetchError, hasMore,
        loadMoreCards, resetInfiniteScrollVariables
    };
}
