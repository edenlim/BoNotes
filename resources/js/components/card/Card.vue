<script setup>
import CardIndicator from './CardIndicator.vue';
import Tags from './Tags.vue';

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
});

// Define explicit click emit to notify Root.vue
const emit = defineEmits(['click']);

const handleLike = () => {
    if (props.data.interaction_status === 'like') {
        props.data.noOfLikes--;
        props.data.interaction_status = null;
    } else {
        if (props.data.interaction_status === 'dislike') props.data.noOfDislikes--;
        props.data.noOfLikes++;
        props.data.interaction_status = 'like';
    }
};

const handleDislike = () => {
    if (props.data.interaction_status === 'dislike') {
        props.data.noOfDislikes--;
        props.data.interaction_status = null;
    } else {
        if (props.data.interaction_status === 'like') props.data.noOfLikes--;
        props.data.noOfDislikes++;
        props.data.interaction_status = 'dislike';
    }
};
</script>

<template>
    <div class="card bg-white w-64 rounded-lg shadow-sm flex flex-col justify-between">
        <CardIndicator
            :data="props.data"
            @click="emit('click')"
            @update:dislike="handleDislike"
            @update:like="handleLike"
        />

        <div class="flex flex-col justify-between p-3 grow">
            <h1 class="text-primary-text font-medium text-card-title mb-1">
                {{ props.data.title }}
            </h1>
            <div class="flex flex-row justify-between">
                <Tags :tags="props.data.tags" />
                <div class="hover-pop download flex items-end ml-2" @click="emit('click')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="size-6 bi bi-download fill-current text-secondary-text cursor-pointer">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</template>
