<script setup>
import Rating from './Rating.vue'; // Import your existing Rating component

const props = defineProps({
    fileType: { type: String, default: 'unknown' },
    noOfLikes: { type: Number, default: 0 },
    noOfDislikes: { type: Number, default: 0 }
});

// Logic to handle the click trigger if needed, or emit an event
const handleClick = () => {
    // You can emit an event to the parent if this card is clickable
    console.log('Card clicked');
};
</script>
<template>
    <div
        @click="handleClick"
        class="w-64 flex flex-col rounded-t-lg transition-colors cursor-pointer"
        :class="fileType === '.pdf' ? 'bg-pdf-data' : 'bg-txt-data'"
    >
        <div class="mx-auto pt-[1.5rem] h-[100px] flex items-center">
            <img
                v-if="fileType === '.pdf'"
                :src="'/resources/images/pdf-icon.svg'"
                alt="PDF Icon"
                class="w-[86px] h-[81px]"
            />

            <img
                v-else
                :src="'/resources/images/txt-icon.svg'"
                alt="Text Icon"
                class="w-[54px] h-[64px]"
            />
        </div>

        <Rating
            :initial-likes="noOfLikes"
            :initial-dislikes="noOfDislikes"
        />
    </div>
</template>
