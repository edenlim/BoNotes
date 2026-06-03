<script setup>
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
    },
    isEditingTitle: {
        type: Boolean,
        default: false
    }
});

const editedTitle = defineModel('editedTitle', { type: String, default: '' });
const emit = defineEmits(['close', 'start-edit', 'save-title', 'cancel-edit']);
</script>

<template>
    <!-- Mobile -->
    <div class="md:hidden flex flex-col p-4 justify-between mx-auto gap-2">
        <div class="flex flex-row justify-between items-center">
            <div
                class="flex items-center gap-2 flex-1 mr-2"
                :class="isOwner ? 'border border-white rounded-lg px-2 py-1' : ''"
            >
                <input
                    v-if="isEditingTitle"
                    v-model="editedTitle"
                    type="text"
                    @keydown.enter="emit('save-title')"
                    @keydown.escape="emit('cancel-edit')"
                    class="bg-transparent text-white text-lg font-bold focus:outline-none w-full"
                    autofocus
                />
                <h1 v-else class="text-white text-lg font-bold">{{ data.title }}</h1>
                <button
                    v-if="isOwner && !isEditingTitle"
                    @click="emit('start-edit')"
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
        <div
            class="flex items-center gap-2"
            :class="isOwner ? 'border border-white rounded-lg px-2 py-1' : ''"
        >
            <input
                v-if="isEditingTitle"
                v-model="editedTitle"
                type="text"
                @keydown.enter="emit('save-title')"
                @keydown.escape="emit('cancel-edit')"
                class="bg-transparent text-white text-lg font-bold focus:outline-none"
                autofocus
            />
            <h1 v-else class="text-white text-lg font-bold">{{ data.title }}</h1>
            <button
                v-if="isOwner && !isEditingTitle"
                @click="emit('start-edit')"
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
