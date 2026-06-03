<script setup>
import { ref } from 'vue';
import CloseButton from "../../../templates/CloseButton.vue";
import Tag from "../../../templates/Tag.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true,
        default: () => ({ tags: [], title: '' })
    },
    isOwner: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['close', 'update-title']);

const isEditingTitle = ref(false);
const editedTitle = ref('');

const startEdit = () => {
    editedTitle.value = props.data.title;
    isEditingTitle.value = true;
};

const saveTitle = () => {
    if (isEditingTitle.value) {
        emit('update-title', editedTitle.value);
        isEditingTitle.value = false;
    }
};

const cancelEdit = () => {
    isEditingTitle.value = false;
};
</script>

<template>
    <!-- Mobile -->
    <div class="md:hidden flex flex-col p-4 justify-between mx-auto gap-2">
        <div class="flex flex-row justify-between items-center">
            <div class="flex items-center gap-2 flex-1 mr-2">
                <input
                    v-if="isEditingTitle"
                    v-model="editedTitle"
                    type="text"
                    @keydown.enter="saveTitle"
                    @keydown.escape="cancelEdit"
                    class="bg-[#2D2D2D] text-white border border-[#444] rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full"
                    autofocus
                />
                <h1 v-else class="text-white text-lg font-bold">{{ data.title }}</h1>
                <button
                    v-if="isOwner && !isEditingTitle"
                    @click="startEdit"
                    class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
                    title="Titel bearbeiten"
                >
                    <img :src="'/resources/images/editing-Pen.svg'" class="w-3.5 h-3.5" alt="Bearbeiten" />
                </button>
            </div>
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

    <!-- Desktop -->
    <div class="hidden md:flex p-4 justify-between mx-auto items-center">
        <div class="flex items-center gap-2">
            <input
                v-if="isEditingTitle"
                v-model="editedTitle"
                type="text"
                @keydown.enter="saveTitle"
                @keydown.escape="cancelEdit"
                class="bg-[#2D2D2D] text-white border border-[#444] rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                autofocus
            />
            <h1 v-else class="text-white text-lg font-bold">{{ data.title }}</h1>
            <button
                v-if="isOwner && !isEditingTitle"
                @click="startEdit"
                class="flex-shrink-0 opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
                title="Titel bearbeiten"
            >
                <img :src="'/resources/images/editing-Pen.svg'" class="w-3.5 h-3.5" alt="Bearbeiten" />
            </button>
        </div>
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
