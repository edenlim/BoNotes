<script setup>
import { ref } from 'vue';
import Rating from "../../card/Rating.vue";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    isOwner: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:like', 'update:dislike', 'delete', 'update-description']);

// Self-contained description editing state
const isEditingDescription = ref(false);
const editedDescription = ref('');

const startEditDescription = () => {
    editedDescription.value = props.data.description;
    isEditingDescription.value = true;
};

const saveDescription = () => {
    if (isEditingDescription.value) {
        emit('update-description', editedDescription.value);
        isEditingDescription.value = false;
    }
};

const cancelDescription = () => {
    isEditingDescription.value = false;
};
</script>

<template>
    <div class="flex flex-col justify-evenly text-[#bbb] text-sm">

        <!-- INFO Bereich -->
        <div class="border-b border-[#3A3A3A] py-8 px-8">
            <div class="flex items-center justify-between mb-1">
                <p class="sidebar-heading">INFO</p>
                <!-- Delete button in INFO header, only for owner -->
                <button
                    v-if="props.isOwner"
                    @click="emit('delete')"
                    class="opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
                    title="Notiz löschen"
                >
                    <img :src="'/resources/images/delete-button.svg'" class="w-4 h-4" alt="Löschen" />
                </button>
            </div>
            <p>Von <span class="font-bold text-[#90CAF9]">{{ props.data.author }}</span></p>
            <p>Hochgeladen <span class="font-bold text-white">{{ props.data.uploadTime }}</span></p>
            <p class="font-bold text-white">{{ props.data.pageLength }} {{ props.data.pageLength > 1 ? "Seiten" : "Seite" }}</p>
        </div>

        <!-- BESCHREIBUNG Bereich -->
        <div class="border-b border-[#3A3A3A] py-8 px-8">
            <div class="flex items-center justify-between mb-1">
                <p class="sidebar-heading">BESCHREIBUNG</p>
                <!-- Pen toggle button, only for owner and when not editing -->
                <button
                    v-if="props.isOwner && !isEditingDescription"
                    @click="startEditDescription"
                    class="opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
                    title="Beschreibung bearbeiten"
                >
                    <img :src="'/resources/images/editing-Pen.svg'" class="w-3.5 h-3.5" alt="Bearbeiten" />
                </button>
            </div>
            <textarea
                v-if="isEditingDescription"
                v-model="editedDescription"
                @keydown.enter.prevent="saveDescription"
                @keydown.escape="cancelDescription"
                class="bg-[#2D2D2D] text-white border border-[#444] rounded p-2 w-full h-24 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                autofocus
            ></textarea>
            <p v-else>{{ props.data.description }}</p>
        </div>

        <!-- HILFREICH Bereich -->
        <div class="border-b border-[#3A3A3A] py-8 px-8">
            <p class="sidebar-heading">HILFREICH?</p>
            <Rating
                :noOfLikes="props.data.noOfLikes"
                :noOfDislikes="props.data.noOfDislikes"
                :userVote="props.data.userVote"
                @update:dislike="emit('update:dislike')"
                @update:like="emit('update:like')"
            />
        </div>

        <!-- Download Bereich -->
        <div class="
            preview-button hover-pop w-40 text-center mx-auto
            p-2 md:p-4 lg:p-8
            my-4 md:my-0
        ">
            Download
        </div>
    </div>
</template>

<style scoped>
</style>
