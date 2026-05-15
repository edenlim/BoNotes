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
<div class="flex flex-col justify-evenly text-[#bbb] text-sm">
    <div class="border-b border-[#3A3A3A] py-8 px-8">
        <p class="sidebar-heading">INFO</p>
        <p>Von <span class="font-bold text-[#90CAF9]">{{props.data.author}}</span></p>
        <p>Hochgeladen <span class="font-bold text-white">{{props.data.uploadTime}}</span></p>
        <p class="font-bold text-white">{{props.data.pageLength}} {{props.data.pageLength > 1 ? "Seiten" : "Seite"}}</p>

    </div>
    <div class="border-b border-[#3A3A3A] py-8 px-8">
        <p class="sidebar-heading">BESCHREIBUNG</p>
        <p>{{props.data.description}}</p>
    </div>
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
    <div class="preview-button hover-pop w-40 text-center mx-auto py-8 px-8">
        Download

    </div>
</div>
</template>

<style scoped>

</style>
