<script setup>
import Rating from './Rating.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
});
const emit = defineEmits(['update:like', 'update:dislike']);

</script>
<template>
    <div
        class="w-64 flex flex-col rounded-t-lg transition-colors cursor-pointer"
        :class="props.data.fileType === '.pdf' ? 'bg-pdf-data' : 'bg-txt-data'"
    >
        <div class="mx-auto pt-[1.5rem] h-[100px] flex items-center">
            <img
                v-if="props.data.fileType === '.pdf'"
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
            :noOfLikes="props.data.noOfLikes"
            :noOfDislikes="props.data.noOfDislikes"
            :interaction_status="props.data.interaction_status"
            @update:dislike="emit('update:dislike')"
            @update:like="emit('update:like')"
        />
    </div>
</template>
