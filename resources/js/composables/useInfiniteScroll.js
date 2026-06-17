import { ref } from "vue";

export function useInfiniteScroll() {
    // Unfiltered (Main Feed) State
    const unfilteredCards = ref([]);
    const lastIndexUnfiltered = ref(0);
    const hasMoreUnfiltered = ref(true);

    // Filtered (Search/Filter Feed) State
    const filteredCards = ref([]);
    const lastIndexFiltered = ref(0);
    const hasMoreFiltered = ref(true);

    const isLoading = ref(false);
    const fetchError = ref(null);

    // Load method that acts depending on active filter parameters
    const loadMoreCards = async (searchQuery = '', activeFilters = []) => {
        const hasSearch = searchQuery.trim() !== '';
        const hasTags = activeFilters.length > 0 && !activeFilters.includes('Alle');
        const isFiltering = hasSearch || hasTags;

        // Check bounds
        if (isFiltering && !hasMoreFiltered.value) return;
        if (!isFiltering && !hasMoreUnfiltered.value) return;

        isLoading.value = true;
        fetchError.value = null;

        try {
            const offset = isFiltering ? lastIndexFiltered.value : lastIndexUnfiltered.value;
            let url = `/api/cards/${offset}`;

            const params = new URLSearchParams();
            if (hasSearch) params.append('search', searchQuery.trim());
            if (hasTags) {
                const tagsToPass = activeFilters.filter(t => t !== 'Alle');
                if (tagsToPass.length > 0) {
                    params.append('tags', tagsToPass.join(','));
                }
            }

            const queryString = params.toString();
            if (queryString) {
                url += `?${queryString}`;
            }

            const response = await fetch(url, {
                method: 'GET',
                headers: {'Accept': 'application/json'},
                credentials: 'include'
            });

            if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
            const newCards = await response.json();

            if (isFiltering) {
                if (newCards.length < 20) {
                    hasMoreFiltered.value = false;
                }
                filteredCards.value = [...filteredCards.value, ...newCards];
                lastIndexFiltered.value += 20;
            } else {
                if (newCards.length < 20) {
                    hasMoreUnfiltered.value = false;
                }
                unfilteredCards.value = [...unfilteredCards.value, ...newCards];
                lastIndexUnfiltered.value += 20;
            }
        } catch (error) {
            console.error("Failed to populate cards:", error);
            fetchError.value = error.message;
        } finally {
            isLoading.value = false;
        }
    };

    const resetFilteredState = () => {
        filteredCards.value = [];
        lastIndexFiltered.value = 0;
        hasMoreFiltered.value = true;
        fetchError.value = null;
    };

    const resetAll = () => {
        unfilteredCards.value = [];
        lastIndexUnfiltered.value = 0;
        hasMoreUnfiltered.value = true;
        resetFilteredState();
    };

    return {
        unfilteredCards,
        filteredCards,
        isLoading,
        fetchError,
        hasMoreUnfiltered,
        hasMoreFiltered,
        loadMoreCards,
        resetFilteredState,
        resetAll
    };
}
