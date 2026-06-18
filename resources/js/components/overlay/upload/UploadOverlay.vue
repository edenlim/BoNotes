<script setup>
import { ref } from 'vue';
import Cookies from 'js-cookie';
import CloseButton from '../../../templates/CloseButton.vue';

const emit = defineEmits(['close', 'uploaded']);

// --- Form State ---
const title       = ref('');
const description = ref('');
const selectedFile = ref(null);
const selectedTags = ref([]);
const isDragging   = ref(false);

// --- UI State ---
const isUploading  = ref(false);
const errorMessage = ref('');
const fileInputRef = ref(null);

const ALL_TAGS = ['mathe', 'informatik', 'wirtschaft', 'maschinenbau'];

// --- File Handling ---
const triggerFileInput = () => fileInputRef.value?.click();

const handleFilePick = (event) => {
    const file = event.target.files[0];
    if (file) setFile(file);
};

const handleDrop = (event) => {
    isDragging.value = false;
    const file = event.dataTransfer?.files[0];
    if (file) setFile(file);
};

const setFile = (file) => {
    const allowed = ['text/plain', 'application/pdf'];
    if (!allowed.includes(file.type)) {
        errorMessage.value = 'Nur .txt und .pdf Dateien werden unterstützt.';
        return;
    }
    if (file.size > 10 * 1024 * 1024) {
        errorMessage.value = 'Die Datei darf maximal 10 MB groß sein.';
        return;
    }
    errorMessage.value = '';
    selectedFile.value = file;
};

const removeFile = () => {
    selectedFile.value = null;
    if (fileInputRef.value) fileInputRef.value.value = '';
};

const toggleTag = (tag) => {
    const idx = selectedTags.value.indexOf(tag);
    if (idx === -1) {
        selectedTags.value.push(tag);
    } else {
        selectedTags.value.splice(idx, 1);
    }
};

const formatFileSize = (bytes) => {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
};

// --- Submit ---
const handleSubmit = async () => {
    errorMessage.value = '';

    if (!title.value.trim()) {
        errorMessage.value = 'Bitte gib einen Titel ein.';
        return;
    }
    if (!description.value.trim()) {
        errorMessage.value = 'Bitte gib eine Beschreibung ein.';
        return;
    }
    if (!selectedFile.value) {
        errorMessage.value = 'Bitte wähle eine Datei aus.';
        return;
    }

    isUploading.value = true;

    try {
        const formData = new FormData();
        formData.append('file',        selectedFile.value);
        formData.append('title',       title.value.trim());
        formData.append('description', description.value.trim());
        formData.append('tags',        JSON.stringify(selectedTags.value));

        const response = await fetch('/api/cards', {
            method: 'POST',
            headers: {
                'Accept':        'application/json',
                'X-XSRF-TOKEN':  Cookies.get('XSRF-TOKEN'),
            },
            credentials: 'include',
            body: formData,
        });

        if (!response.ok) {
            const err = await response.json().catch(() => ({}));
            throw new Error(err.message || `Fehler ${response.status}`);
        }

        const newCard = await response.json();

        // Notify parent to prepend card to feed
        emit('uploaded', newCard);
        emit('close');

    } catch (err) {
        errorMessage.value = err.message || 'Upload fehlgeschlagen. Bitte versuche es erneut.';
    } finally {
        isUploading.value = false;
    }
};
</script>

<template>
    <div class="bg-white w-[30rem] max-w-[92vw] rounded-2xl shadow-xl">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 pt-5 pb-3 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-primary-text">Notiz hochladen</h2>
            <CloseButton @close="emit('close')" />
        </div>

        <form class="px-6 pb-6 pt-4 space-y-5" @submit.prevent="handleSubmit" novalidate>

            <!-- Drop Zone -->
            <div
                class="drop-zone border-2 border-dashed rounded-xl p-6 text-center cursor-pointer transition-colors"
                :class="isDragging ? 'border-primary bg-primary/5' : 'border-gray-300 hover:border-primary/50'"
                @click="triggerFileInput"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="handleDrop"
            >
                <input
                    ref="fileInputRef"
                    type="file"
                    accept=".txt,.pdf"
                    class="hidden"
                    @change="handleFilePick"
                />

                <!-- No file selected -->
                <div v-if="!selectedFile" class="flex flex-col items-center gap-2 pointer-events-none">
                    <div class="text-3xl">📄</div>
                    <div class="text-sm font-medium text-primary-text">
                        Datei hier ablegen oder <span class="text-primary underline">auswählen</span>
                    </div>
                    <div class="text-xs text-secondary-text">.txt und .pdf · max. 10 MB</div>
                </div>

                <!-- File selected preview -->
                <div v-else class="flex items-center justify-between gap-3" @click.stop>
                    <div class="flex items-center gap-3 text-left min-w-0">
                        <div class="flex-shrink-0">
                            <img
                                :src="selectedFile.name.endsWith('.pdf') ? '/resources/images/pdf-icon.svg' : '/resources/images/txt-icon.svg'"
                                :alt="selectedFile.name.endsWith('.pdf') ? 'PDF Icon' : 'Text Icon'"
                                :class="selectedFile.name.endsWith('.pdf') ? 'w-[86px] h-[81px]' : 'w-[54px] h-[64px]'"
                            />
                        </div>

                        <div class="min-w-0">
                            <div class="text-sm font-medium text-primary-text truncate">{{ selectedFile.name }}</div>
                            <div class="text-xs text-secondary-text">{{ formatFileSize(selectedFile.size) }}</div>
                        </div>
                    </div>
                    <button
                        type="button"
                        class="text-gray-400 hover:text-red-500 transition-colors text-lg flex-shrink-0 px-1"
                        @click.stop="removeFile"
                        title="Datei entfernen"
                    >✕</button>
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-sm font-medium text-primary-text mb-1" for="upload-title">
                    Titel <span class="text-red-400">*</span>
                </label>
                <input
                    id="upload-title"
                    v-model="title"
                    type="text"
                    placeholder="z.B. Mathe Zusammenfassung"
                    maxlength="255"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition"
                />
            </div>

            <!-- Description -->
            <div>
                <label class="block text-sm font-medium text-primary-text mb-1" for="upload-description">
                    Beschreibung <span class="text-red-400">*</span>
                </label>
                <textarea
                    id="upload-description"
                    v-model="description"
                    rows="3"
                    placeholder="z.B. Es handelt Sich um Ein Text der Folgendes Beinhaltet..."
                    maxlength="2000"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary transition resize-none"
                ></textarea>
            </div>

            <!-- Tags -->
            <div>
                <label class="block text-sm font-medium text-primary-text mb-2">Labels</label>
                <div class="flex flex-wrap gap-2">
                    <button
                        v-for="tag in ALL_TAGS"
                        :key="tag"
                        type="button"
                        class="px-3 py-1 text-xs font-medium rounded-full border transition-all cursor-pointer"
                        :class="selectedTags.includes(tag)
                            ? 'bg-primary text-white border-primary'
                            : 'bg-white text-gray-500 border-gray-300 hover:border-primary/50'"
                        @click="toggleTag(tag)"
                    >
                        {{ tag }}
                    </button>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="errorMessage" class="text-sm text-red-500 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                {{ errorMessage }}
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-1">
                <button
                    type="button"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-secondary-text cursor-pointer hover:bg-gray-50 transition"
                    :disabled="isUploading"
                    @click="emit('close')"
                >
                    Abbrechen
                </button>
                <button
                    type="submit"
                    class="px-5 py-2 text-sm rounded-lg bg-primary text-white cursor-pointer hover:opacity-90 transition flex items-center gap-2 disabled:opacity-60"
                    :disabled="isUploading"
                >
                    <span v-if="isUploading" class="loader"></span>
                    {{ isUploading ? 'Wird hochgeladen...' : 'Hochladen' }}
                </button>
            </div>

        </form>
    </div>
</template>

<style scoped>
.loader {
    width: 14px;
    height: 14px;
    border: 2px solid rgba(255,255,255,0.4);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: inline-block;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>
