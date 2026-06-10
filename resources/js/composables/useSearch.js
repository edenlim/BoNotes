import { ref, watch, computed } from 'vue';

export function useSearch(responseData) {
    const activeFilter = ref(['Alle']);
    const rawSearchQuery = ref('');
    const debouncedSearchQuery = ref('');
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
                return card.tags.every(cardTag => activeFiltersLower.includes(cardTag.toLowerCase()));
            });
        }

        if (debouncedSearchQuery.value !== '') {
            cards = cards.filter(card => card.title.toLowerCase().includes(debouncedSearchQuery.value));
        }

        return cards;
    });

    return { activeFilter, rawSearchQuery, filteredCards };
}
