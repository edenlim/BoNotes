import { ref, computed } from 'vue';

export function useRouting() {
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

    return { currentNoteId, showNoteOverlay, parseUrlRoute, navigateToNote };
}
