<script setup>
import InformationHeadbar from "./InformationHeadbar.vue";
import Display from "./Display.vue";
import InformationSidebar from "./InformationSidebar.vue";
import { computed } from "vue";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    session: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['close', 'update:like', 'update:dislike', 'delete-note', 'update-note']);

const isOwner = computed(() => {
    return props.session && props.session.userid == props.data.author;
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
}));

const handleUpdateTitle = (newTitle) => {
    emit('update-note', { id: props.data.id, title: newTitle });
};

const handleUpdateDescription = (newDescription) => {
    emit('update-note', { id: props.data.id, description: newDescription });
};

const handleDelete = () => {
    if (confirm("Möchtest du diese Notiz wirklich löschen?")) {
        emit('delete-note', props.data.id);
    }
};
</script>

<template>
    <div class="bg-preview-bg no-scrollbar">
        <InformationHeadbar
            :data="informationHeadbarData"
            :isOwner="isOwner"
            @close="emit('close')"
            @update-title="handleUpdateTitle"
        />
        <div class="flex flex-col md:flex-row md:grid grid-cols-3">
            <Display
                :fileType="props.data.fileType"
                :pageLength="props.data.pageLength"
                class="col-span-2"
            />
            <InformationSidebar
                :data="informationSidebarData"
                :isOwner="isOwner"
                @update:dislike="emit('update:dislike')"
                @update:like="emit('update:like')"
                @delete="handleDelete"
                @update-description="handleUpdateDescription"
                class="col-span-1"
            />
        </div>
    </div>
</template>
