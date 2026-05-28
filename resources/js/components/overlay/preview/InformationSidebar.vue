<script setup>
import Rating from "../../card/Rating.vue";

const props = defineProps({
    data:{
        type: Object,
        required: true
    },
    isOwner: {
        type: Boolean,
        default: false
    },
    isEditing: {
        type: Boolean,
        default: false
    }
});
const descriptionModel = defineModel('description', { type: String });
const emit = defineEmits(['update:like', 'update:dislike', 'edit', 'delete', 'save', 'cancel']);
</script>

<template>
    <div class="flex flex-col justify-evenly text-[#bbb] text-sm">
        <!-- INFO Bereich (wiederhergestellt) -->
        <div class="border-b border-[#3A3A3A] py-8 px-8">
            <p class="sidebar-heading">INFO</p>
            <p>Von <span class="font-bold text-[#90CAF9]">{{ props.data.author }}</span></p>
            <p>Hochgeladen <span class="font-bold text-white">{{ props.data.uploadTime }}</span></p>
            <p class="font-bold text-white">{{ props.data.pageLength }} {{ props.data.pageLength > 1 ? "Seiten" : "Seite" }}</p>
        </div>

        <!-- AKTIONEN Bereich -->
        <div v-if="props.isOwner" class="border-b border-[#3A3A3A] py-6 px-8 flex flex-col gap-2">
            <p class="sidebar-heading">AKTIONEN</p>

            <div v-if="!isEditing" class="flex gap-2">
                <!-- Edit Button -->
                <button
                    @click="emit('edit')"
                    class="flex items-center gap-2 bg-[#90CAF9] hover:bg-[#64B5F6] text-black px-3 py-1.5 rounded-lg font-medium cursor-pointer transition-colors text-xs"
                >
                    <img :src="'/resources/images/editing-Pen.svg'" class="w-4 h-4" alt="Bearbeiten" />
                    Bearbeiten
                </button>

                <!-- Delete Button -->
                <button
                    @click="emit('delete')"
                    class="flex items-center gap-2 bg-red-400 hover:bg-red-500 text-white px-3 py-1.5 rounded-lg font-medium cursor-pointer transition-colors text-xs"
                >
                    <img :src="'/resources/images/delete-button.svg'" class="w-4 h-4" alt="Löschen" />
                    Löschen
                </button>
            </div>

            <div v-else class="flex gap-2">
                <!-- Save Button -->
                <button
                    @click="emit('save')"
                    class="flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg font-medium cursor-pointer transition-colors text-xs"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Speichern
                </button>

                <!-- Cancel Button -->
                <button
                    @click="emit('cancel')"
                    class="flex items-center gap-1.5 bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-lg font-medium cursor-pointer transition-colors text-xs"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                    Abbrechen
                </button>
            </div>
        </div>

        <!-- BESCHREIBUNG Bereich -->
        <div class="border-b border-[#3A3A3A] py-8 px-8">
            <p class="sidebar-heading">BESCHREIBUNG</p>
            <textarea
                v-if="isEditing"
                v-model="descriptionModel"
                class="bg-[#2D2D2D] text-white border border-[#444] rounded p-2 w-full h-24 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
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
