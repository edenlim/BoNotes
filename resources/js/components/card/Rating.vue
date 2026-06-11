<script setup>
import { ref } from 'vue';
const props = defineProps({
    noOfLikes: { type: Number, required: true },
    noOfDislikes: { type: Number, required: true },
    interaction_status: { type: String, default: null }
});

const emit = defineEmits(['update:like', 'update:dislike']);
</script>

<template>
    <div class="flex gap-4 select-none m-3">
        <!-- Like Button -->
        <div @click.stop="emit('update:like')" class="inline-flex items-center gap-1 cursor-pointer">
            <svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    class="fill-current transition-colors"
                    :class="props.interaction_status === 'like' ? 'text-like-green-active' : 'text-like-green'"
                    d="M10.1 0.369798L5.48333 4.98646C5.175 5.2948 5 5.7198 5 6.16147V14.4865C5 15.4031 5.75 16.1531 6.66667 16.1531H14.1667C14.8333 16.1531 15.4333 15.7531 15.7 15.1448L18.4167 8.80313C19.1167 7.15313 17.9083 5.3198 16.1167 5.3198H11.4083L12.2 1.50313C12.2833 1.08647 12.1583 0.661465 11.8583 0.361465C11.3667 -0.121868 10.5833 -0.121868 10.1 0.369798ZM1.66667 16.1531C2.58333 16.1531 3.33333 15.4031 3.33333 14.4865V7.8198C3.33333 6.90313 2.58333 6.15313 1.66667 6.15313C0.75 6.15313 0 6.90313 0 7.8198V14.4865C0 15.4031 0.75 16.1531 1.66667 16.1531Z"
                />
            </svg>
            <p :class="props.interaction_status === 'like' ? 'text-like-green-active' : 'text-like-green'">
                {{ noOfLikes }}
            </p>
        </div>

        <!-- Dislike Button -->
        <div @click.stop="emit('update:dislike')" class="inline-flex items-center gap-1 cursor-pointer">
            <svg width="19" height="17" viewBox="0 0 19 17" fill="none" xmlns="http://www.w3.org/2000/svg" class="rotate-180">
                <path
                    class="fill-current transition-colors"
                    :class="props.interaction_status === 'dislike' ? 'text-dislike-red-active' : 'text-dislike-red'"
                    d="M10.1 0.369798L5.48333 4.98646C5.175 5.2948 5 5.7198 5 6.16147V14.4865C5 15.4031 5.75 16.1531 6.66667 16.1531H14.1667C14.8333 16.1531 15.4333 15.7531 15.7 15.1448L18.4167 8.80313C19.1167 7.15313 17.9083 5.3198 16.1167 5.3198H11.4083L12.2 1.50313C12.2833 1.08647 12.1583 0.661465 11.8583 0.361465C11.3667 -0.121868 10.5833 -0.121868 10.1 0.369798ZM1.66667 16.1531C2.58333 16.1531 3.33333 15.4031 3.33333 14.4865V7.8198C3.33333 6.90313 2.58333 6.15313 1.66667 6.15313C0.75 6.15313 0 6.90313 0 7.8198V14.4865C0 15.4031 0.75 16.1531 1.66667 16.1531Z"
                />
            </svg>
            <p :class="props.interaction_status === 'dislike' ? 'text-dislike-red-active' : 'text-dislike-red'">
                {{ noOfDislikes }}
            </p>
        </div>
    </div>
</template>
