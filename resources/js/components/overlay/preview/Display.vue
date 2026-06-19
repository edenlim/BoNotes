<script setup>
import { ref, shallowRef, computed, watch, onBeforeUnmount, nextTick } from 'vue';
import * as pdfjsLib from 'pdfjs-dist';

// ── pdfjs Worker (Vite-compatible: new URL keeps the worker as a separate asset chunk) ──
pdfjsLib.GlobalWorkerOptions.workerSrc = new URL(
    'pdfjs-dist/build/pdf.worker.min.mjs',
    import.meta.url
).toString();

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});
const emit = defineEmits(['total-pages']);


const fileUrl = computed(() =>
    props.data?.file_path ? `/storage/${props.data.file_path}` : null
);
const isPdf = computed(() => props.data?.fileType === '.pdf');
const isTxt = computed(() => props.data?.fileType === '.txt');


//  PDF RENDERING

const pdfCanvasRef   = ref(null);
const pdfDoc = shallowRef(null);
const currentPdfPage = ref(1);
const totalPdfPages  = ref(0);
const isPdfLoading   = ref(false);
const pdfError       = ref(null);

const renderPage = async (num) => {
    await nextTick(); // make sure the canvas is in the DOM
    if (!pdfDoc.value || !pdfCanvasRef.value) return;

    try {
        const page = await pdfDoc.value.getPage(num);
        const canvas = pdfCanvasRef.value;
        const ctx = canvas.getContext('2d');

        const containerWidth = canvas.parentElement?.clientWidth || 640;
        const baseViewport   = page.getViewport({ scale: 1 });
        const scale          = containerWidth / baseViewport.width;
        const viewport       = page.getViewport({ scale });

        // Use devicePixelRatio for sharp rendering on HiDPI screens
        const dpr = window.devicePixelRatio || 1;
        canvas.width  = viewport.width  * dpr;
        canvas.height = viewport.height * dpr;
        canvas.style.width  = `${viewport.width}px`;
        canvas.style.height = `${viewport.height}px`;
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

        await page.render({ canvasContext: ctx, viewport }).promise;
    } catch (err) {
        console.error('Display: Fehler beim Anzeigen der PDF-Seite:', err);
    }
};

const loadPdf = async (url) => {
    if (!url || typeof url !== 'string') return;

    // Destroy previous document to free memory
    if (pdfDoc.value) {
        try {
            pdfDoc.value.destroy();
        } catch (e) {
            console.error('Fehler beim Zerstören der alten PDF:', e);
        }
        pdfDoc.value = null;
    }

    isPdfLoading.value   = true;
    pdfError.value       = null;
    currentPdfPage.value = 1;
    totalPdfPages.value  = 0;

    try {
        const loadingTask   = pdfjsLib.getDocument({ url });
        pdfDoc.value        = await loadingTask.promise;
        totalPdfPages.value = pdfDoc.value.numPages;

        emit('total-pages', pdfDoc.value.numPages);

        isPdfLoading.value = false;
        await nextTick(); // Canvas im DOM mounten lassen
        await renderPage(1);
    } catch (err) {
        console.error('Display: Fehler beim Laden der PDF:', err);
        pdfError.value     = err.message ?? 'Unbekannter Fehler';
        isPdfLoading.value = false;
    }
};

const prevPdfPage = () => {
    if (currentPdfPage.value <= 1) return;
    currentPdfPage.value--;
    renderPage(currentPdfPage.value);
};

const nextPdfPage = () => {
    if (currentPdfPage.value >= totalPdfPages.value) return;
    currentPdfPage.value++;
    renderPage(currentPdfPage.value);
};


//  TXT RENDERING

const LINES_PER_PAGE = 40;

const allLines       = ref([]);
const currentTxtPage = ref(1);
const isTxtLoading   = ref(false);
const txtError       = ref(null);

const totalTxtPages = computed(() =>
    Math.max(1, Math.ceil(allLines.value.length / LINES_PER_PAGE))
);

const txtStatus = computed(() => {
    if (totalTxtPages.value <= 1)                     return 'singlePage';
    if (currentTxtPage.value === 1)                   return 'firstPage';
    if (currentTxtPage.value === totalTxtPages.value) return 'lastPage';
    return null;
});

const visibleLines = computed(() => {
    const start = (currentTxtPage.value - 1) * LINES_PER_PAGE;
    return allLines.value.slice(start, start + LINES_PER_PAGE);
});

const fetchTextContent = async () => {
    if (!fileUrl.value) return;

    isTxtLoading.value  = true;
    txtError.value      = null;
    allLines.value      = [];
    currentTxtPage.value = 1;

    try {
        const response = await fetch(fileUrl.value, { credentials: 'include' });
        if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        const text = await response.text();
        allLines.value = text.split('\n');
        emit('total-pages', totalTxtPages.value);
    } catch (err) {
        console.error('Display: Fehler beim Laden der TXT-Datei:', err);
        txtError.value = err.message ?? 'Unbekannter Fehler';
    } finally {
        isTxtLoading.value = false;
    }
};

const prevTxtPage = () => {
    if (currentTxtPage.value > 1) currentTxtPage.value--;
};

const nextTxtPage = () => {
    if (currentTxtPage.value < totalTxtPages.value) currentTxtPage.value++;
};

//  Lifecycle & Watchers

const load = () => {
    if (isPdf.value) {
        loadPdf(fileUrl.value);
    } else if (isTxt.value) {
        fetchTextContent();
    }
};

watch(() => props.data?.file_path, () => { load(); }, { immediate: true });

onBeforeUnmount(() => {
    if (pdfDoc.value) {
        try {
            pdfDoc.value.destroy();
        } catch (e) {}
    }
});
</script>

<template>
    <div class="text-white flex flex-col">

        <!-- PDF Viewer -->
        <template v-if="isPdf">
            <!-- Content area -->
            <div class="flex flex-col h-[50vh] md:h-[60vh] bg-pdf-data overflow-y-auto items-center">

                <!-- No file_path -->
                <div v-if="!fileUrl" class="flex items-center justify-center h-full text-[#555] text-sm">
                    Kein Dateipfad verfügbar.
                </div>

                <!-- Loading -->
                <div v-else-if="isPdfLoading" class="flex items-center justify-center h-full gap-2 text-gray-500">
                    <svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span class="text-sm">PDF wird geladen…</span>
                </div>

                <!-- Error -->
                <div v-else-if="pdfError" class="flex flex-col items-center justify-center h-full gap-2 text-red-400 px-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <p class="text-sm font-semibold">PDF konnte nicht geladen werden</p>
                    <p class="text-xs text-red-300 break-all">{{ pdfError }}</p>
                    <!-- Use load() so fileUrl.value is read in JS context, not as a raw ComputedRef -->
                    <button @click="load()" class="mt-2 preview-button hover-pop text-xs">
                        Erneut versuchen
                    </button>
                </div>

                <!-- Canvas -->
                <div v-else class="w-full overflow-auto">
                    <canvas ref="pdfCanvasRef" class="block mx-auto shadow-sm" />
                </div>
            </div>

            <!-- PDF Pagination bar -->
            <div class="flex flex-row items-center justify-center gap-4 py-4 bg-[#323232] border-[#202020] border-r">
                <template v-if="totalPdfPages > 1 && !isPdfLoading">
                    <button
                        class="preview-button"
                        :class="currentPdfPage <= 1 ? 'text-[#555] cursor-not-allowed' : 'hover-pop'"
                        :disabled="currentPdfPage <= 1"
                        @click="prevPdfPage"
                    >
                        prev
                    </button>
                    <p>Seite {{ currentPdfPage }} von {{ totalPdfPages }}</p>
                    <button
                        class="preview-button"
                        :class="currentPdfPage >= totalPdfPages ? 'text-[#555] cursor-not-allowed' : 'hover-pop'"
                        :disabled="currentPdfPage >= totalPdfPages"
                        @click="nextPdfPage"
                    >
                        next
                    </button>
                </template>
                <p v-else-if="!isPdfLoading && totalPdfPages === 1">Eine Seite</p>
            </div>
        </template>
        <!-- Text Viewer -->
        <template v-else-if="isTxt">
            <!-- Content area -->
            <div class="flex flex-col h-[50vh] md:h-[60vh] bg-txt-data overflow-hidden">

                <!-- No file_path -->
                <div v-if="!fileUrl" class="flex items-center justify-center h-full text-[#555] text-sm">
                    Kein Dateipfad verfügbar.
                </div>

                <!-- Loading -->
                <div v-else-if="isTxtLoading" class="flex items-center justify-center h-full gap-2 text-[#555]">
                    <svg class="animate-spin w-5 h-5 text-[#777]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                    </svg>
                    <span class="text-sm">Wird geladen…</span>
                </div>

                <!-- Error -->
                <div v-else-if="txtError" class="flex flex-col items-center justify-center h-full gap-2 text-red-400 px-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <p class="text-sm font-semibold">Datei konnte nicht geladen werden</p>
                    <p class="text-xs text-red-300 break-all">{{ txtError }}</p>
                    <button @click="fetchTextContent" class="mt-2 preview-button hover-pop text-xs">
                        Erneut versuchen
                    </button>
                </div>

                <!-- Text content -->
                <div v-else class="h-full overflow-y-auto px-6 py-5 font-mono text-sm text-primary-text leading-relaxed whitespace-pre-wrap break-words">
                    <span v-for="(line, idx) in visibleLines" :key="idx" class="block">{{ line }}</span>
                    <span v-if="visibleLines.length === 0 && !isTxtLoading" class="text-[#999] italic">
                        (Leere Seite)
                    </span>
                </div>
            </div>

            <!-- TXT Pagination bar -->
            <div
                v-if="txtStatus !== 'singlePage'"
                class="flex flex-row items-center justify-center gap-4 py-4 bg-[#323232] border-[#202020] border-r"
            >
                <button
                    class="preview-button"
                    :class="txtStatus === 'firstPage' ? 'text-[#555] cursor-not-allowed' : 'hover-pop'"
                    :disabled="txtStatus === 'firstPage'"
                    @click="prevTxtPage"
                >
                    prev
                </button>
                <p>Seite {{ currentTxtPage }} von {{ totalTxtPages }}</p>
                <button
                    class="preview-button"
                    :class="txtStatus === 'lastPage' ? 'text-[#555] cursor-not-allowed' : 'hover-pop'"
                    :disabled="txtStatus === 'lastPage'"
                    @click="nextTxtPage"
                >
                    next
                </button>
            </div>
            <div v-else class="flex flex-row items-center justify-center gap-4 py-8 bg-[#323232] border-[#202020] border-r">
                <p>Eine Seite</p>
            </div>
        </template>
        <!-- Unknown Filetyp or error with the file -->
        <template v-else>
            <div class="flex items-center justify-center h-[50vh] bg-[#3a3a3a] text-[#888] text-sm">
                Keine Vorschau verfügbar für diesen Dateityp.
            </div>
            <div class="flex flex-row items-center justify-center gap-4 py-8 bg-[#323232] border-[#202020] border-r">
                <p>—</p>
            </div>
        </template>

    </div>
</template>

<style scoped>
</style>
