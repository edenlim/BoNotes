<script setup>

import CloseButton from "../../../templates/CloseButton.vue";
import Tag from "../../../templates/Tag.vue";
const props = defineProps({
    data: {
        type: Object,
        required: true,
        default: () => ({ tags: [], title: '' })
    },
    isEditing: {
        type: Boolean,
        default: false
    }
});
const emit = defineEmits(['close']);
const titleModel = defineModel('title', { type: String });

</script>

<template>
    <div class="md:hidden flex flex-col p-4 justify-between mx-auto gap-2">
        <div class="flex flex-row justify-between items-center">
            <!-- Korrigiert: Input-Tag geschlossen und v-else hinzugefügt -->
            <input
                v-if="isEditing"
                v-model="titleModel"
                type="text"
                class="bg-[#2D2D2D] text-white border border-[#444] rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full mr-2"
            />
            <h1 v-else class="text-white text-lg font-bold">{{ data.title }}</h1>
            <CloseButton @close="emit('close')" />
        </div>
        <div class="flex flex-row gap-[0.75rem]">
            <Tag
                v-for="(tag, index) in data.tags"
                :key="index"
                :content="tag.toLowerCase()"
                class="px-2 py-1 text-card-tags font-medium"
            />
        </div>
    </div>

    <div class="hidden md:flex p-4 justify-between mx-auto items-center">
        <!-- Korrigiert: v-else hinzugefügt -->
        <input
            v-if="isEditing"
            v-model="titleModel"
            type="text"
            class="bg-[#2D2D2D] text-white border border-[#444] rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64 mr-4"
        />
        <h1 v-else class="text-white text-lg font-bold">{{ data.title }}</h1>
        <div class="flex flex-row gap-[0.75rem] items-center">
            <Tag
                v-for="(tag, index) in data.tags"
                :key="index"
                :content="tag.toLowerCase()"
                class="px-2 py-1 text-card-tags font-medium"
            />
            <CloseButton @close="emit('close')" />
        </div>
    </div>
</template>

<style scoped>

</style>
