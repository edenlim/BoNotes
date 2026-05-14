<script setup>
import { ref } from 'vue';
import CardIndicator from './CardIndicator.vue';
import Tags from './Tags.vue';
import Background from '../overlay/Background.vue'; // Import Shell
import Preview from '../overlay/preview/Preview.vue'; // Import Content

const props = defineProps({
    title: { type: String, default: 'No title' },
    fileType: { type: String, default: 'unknown' },
    noOfLikes: { type: Number, default: 0 },
    noOfDislikes: { type: Number, default: 0 },
    tags: { type: Array, default: () => [] }
});

const isOverlayOpen = ref(false);

const toggleOverlay = () => {
    isOverlayOpen.value = !isOverlayOpen.value;
};
</script>

<template>
    <div class="card bg-white w-64 rounded-lg shadow-sm">
        <CardIndicator
            :file-type="fileType"
            :no-of-likes="noOfLikes"
            :no-of-dislikes="noOfDislikes"
            @click="toggleOverlay"
        />

        <div class="flex flex-row justify-between p-3">
            <div>
                <h1 class="text-primary-text font-medium text-card-title mb-1">
                    {{ title }}
                </h1>
                <Tags :tags="tags" />
            </div>

            <div class="download flex items-end" @click="toggleOverlay">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="size-6 bi bi-download fill-current text-secondary-text cursor-pointer">
                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                </svg>
            </div>
        </div>

        <!-- NEW OVERLAY LOGIC -->
        <Background :show="isOverlayOpen" @close="isOverlayOpen = false">
            <!-- Everything inside here goes into the <slot /> of Background.vue -->
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4">Preview: {{ title }}</h2>
                <Preview :tags="tags" />

                <button
                    @click="isOverlayOpen = false"
                    class="mt-6 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300"
                >
                    Close
                </button>
            </div>
        </Background>
    </div>
</template>
