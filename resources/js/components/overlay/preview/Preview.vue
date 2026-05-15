<script setup>
import InformationHeadbar from "./InformationHeadbar.vue";
import Display from "./Display.vue";
import InformationSidebar from "./InformationSidebar.vue";
import {computed} from "vue";
import Rating from "../../card/Rating.vue";


const props = defineProps({
    data:{
        type: Object,
        required: true
    }
});

const informationHeadbarData = computed(() => ({
    tags: props.data.tags,
    title: props.data.title
}));

const informationSidebarData = computed(() => ({
    author: props.data.author,
    uploadTime: props.data.uploadTime,
    pageLength: props.data.pageLength,
    description: props.data.description,
    noOfLikes: props.data.noOfLikes,
    noOfDislikes: props.data.noOfDislikes,
    userVote: props.data.userVote,

}))

const emit = defineEmits(['close','update:like', 'update:dislike']);
</script>

<template>
    <div class="bg-preview-bg">
        <InformationHeadbar :data="informationHeadbarData" @close="emit('close')"/>
        <div class="flex flex-row grid grid-cols-3">
            <Display class="col-span-2"/>
            <InformationSidebar
                :data="informationSidebarData"
                @update:dislike="emit('update:dislike')"
                @update:like="emit('update:like')"
                class="col-span-1"
            />
        </div>
    </div>

</template>
