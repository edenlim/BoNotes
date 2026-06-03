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
    },
    isEditingTitle: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:like', 'update:dislike', 'delete', 'update-description', 'save-title']);

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

                <button
                    v-if="props.isOwner && !isEditingDescription"
                    @click="startEditDescription"
                    class="opacity-50 hover:opacity-100 transition-opacity cursor-pointer"
                    title="Beschreibung bearbeiten"
                >
                    <img :src="'/resources/images/editing-Pen.svg'" class="w-3.5 h-3.5" alt="Bearbeiten" />
                </button>
            </div>
            <div class="flex items-center gap-2 flex-1" :class="isEditingDescription ?  'border border-white rounded-lg p-2 -mx-2' : ''">
                <textarea
                    v-if="isEditingDescription"
                    v-model="editedDescription"
                    @keydown.enter.prevent="saveDescription"
                    @keydown.escape="cancelDescription"
                    class="bg-transparent text-[#bbb] w-full text-sm focus:outline-none resize-none leading-relaxed"
                    style="min-height: 0; height: auto; field-sizing: content;"
                    autofocus
                ></textarea>
                <p v-else class="leading-relaxed">{{ props.data.description }}</p>
            </div>
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

        <!-- Save Changes / Download Button -->
        <div
            v-if="isEditingDescription"
            @click="saveDescription"
            class="preview-button hover-pop w-40 text-center mx-auto p-2 md:p-4 lg:p-4 my-4 md:my-0 cursor-pointer"
        >
            Speichern
        </div>
        <div
            v-else-if="props.isEditingTitle"
            @click="emit('save-title')"
            class="preview-button hover-pop w-40 text-center mx-auto p-2 md:p-4 lg:p-4 my-4 md:my-0 cursor-pointer"
        >
             Speichern
        </div>
        <div v-else class="
            preview-button hover-pop w-40 text-center mx-auto
            p-2 md:p-4 lg:p-4
            my-4 md:my-0
        ">
            Download
        </div>
    </div>
</template>

<style scoped>
</style>
