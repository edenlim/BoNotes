<script setup>
import Rating from "../../card/Rating.vue";
const props = defineProps({
    data:{
        type: Object,
        required: true
    }
});
const emit = defineEmits(['update:like', 'update:dislike']);

</script>

<template>
<div class="flex flex-col justify-evenly text-white">
    <div class="border-b border-[#3A3A3A]">
        <p>Info</p>
        <p>Von {{props.data.author}}</p>
        <p>Hochgeladen {{props.data.uploadTime}}</p>
        <p>{{props.data.pageLength}} {{props.data.pageLength > 1 ? "Seiten" : "Seite"}}</p>

    </div>
    <div class="border-b border-[#3A3A3A]">
        <p>Beschreibung</p>
        <p>{{props.data.description}}</p>
    </div>
    <div class="border-b border-[#3A3A3A]">
        <p>Rating</p>
        <Rating
            :noOfLikes="props.data.noOfLikes"
            :noOfDislikes="props.data.noOfDislikes"
            :userVote="props.data.userVote"
            @update:dislike="emit('update:dislike')"
            @update:like="emit('update:like')"
        />
    </div>
    <div class="preview-button hover-pop w-40 text-center mx-auto">
        Download

    </div>
</div>
</template>

<style scoped>

</style>
