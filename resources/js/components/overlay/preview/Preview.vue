<script setup>
import InformationHeadbar from "./InformationHeadbar.vue";
import Display from "./Display.vue";
import InformationSidebar from "./InformationSidebar.vue";
import {computed} from "vue"
import { ref } from 'vue';

import Rating from "../../card/Rating.vue";


const props = defineProps({
    data:{
        type: Object,
        required: true
    },
    session: {
        type: Object,
        default: null
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

const emit = defineEmits(['close','update:like', 'update:dislike','delete-note', 'update-note']);

const isOwner = computed(() => {
    return props.session && props.session.userid == props.data.author;
});

const isEditing = ref(false);
const editedTitle = ref('');
const editedDescription = ref('');

const startEdit = () => {
    editedTitle.value = props.data.title;
    editedDescription.value = props.data.description;
    isEditing.value = true;
};

const cancelEdit = () => {
    isEditing.value = false;
};

const saveEdit = () => {
    emit('update-note', {
        id: props.data.id,
        title: editedTitle.value,
        description: editedDescription.value
    });
    isEditing.value = false;
};

const deleteNote = () => {
    if (confirm("Möchtest du diese Notiz wirklich löschen?")) {
        emit('delete-note', props.data.id);
    }
};
</script>

<template>
    <div class="bg-preview-bg no-scrollbar">
        <InformationHeadbar :data="informationHeadbarData" :isEditing="isEditing" v-model:title="editedTitle" @close="emit('close')"/>
        <div class="flex flex-col md:flex-row md:grid grid-cols-3">
            <Display
                :fileType="props.data.fileType"
                :pageLength="props.data.pageLength"
                class="col-span-2"
            />
            <InformationSidebar
                :data="informationSidebarData"
                @update:dislike="emit('update:dislike')"
                @update:like="emit('update:like')"
                :isOwner="isOwner"
                :isEditing="isEditing"
                v-model:description="editedDescription"
                @edit="startEdit"
                @delete="deleteNote"
                @save="saveEdit"
                @cancel="cancelEdit"
                class="col-span-1"
            />
        </div>
    </div>

</template>
