<script setup>
import InformationHeadbar from "./InformationHeadbar.vue";
import Display from "./Display.vue";
import InformationSidebar from "./InformationSidebar.vue";
import { computed, ref } from "vue";

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
    return props.session && props.session.userid == props.data.user_id;
});

// Title editing state lives here so Sidebar can react to it
const isEditingTitle = ref(false);
const editedTitle = ref('');

const startTitleEdit = () => {
    editedTitle.value = props.data.title;
    isEditingTitle.value = true;
};

const saveTitle = () => {
    emit('update-note', { id: props.data.id, title: editedTitle.value });
    isEditingTitle.value = false;
};

const cancelTitleEdit = () => {
    isEditingTitle.value = false;
};

const informationHeadbarData = computed(() => ({
    tags: props.data.tags,
    title: props.data.title
}));

const informationSidebarData = computed(() => ({
    user: props.data.user,
    uploadTime: props.data.uploadTime,
    page_length: props.data.page_length,
    description: props.data.description,
    noOfLikes: props.data.noOfLikes,
    noOfDislikes: props.data.noOfDislikes,
    interaction_status: props.data.interaction_status,
}));

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
    <div class="bg-preview-bg no-scrollbar w-[85vw]">
        <InformationHeadbar
            :data="informationHeadbarData"
            :isOwner="isOwner"
            :isEditingTitle="isEditingTitle"
            v-model:editedTitle="editedTitle"
            @close="emit('close')"
            @start-edit="startTitleEdit"
            @save-title="saveTitle"
            @cancel-edit="cancelTitleEdit"
        />
        <div class="flex flex-col md:flex-row md:grid grid-cols-3">
            <Display
                :fileType="props.data.fileType"
                :page_length="props.data.page_length"
                class="col-span-2"
            />
            <InformationSidebar
                :data="informationSidebarData"
                :isOwner="isOwner"
                :isEditingTitle="isEditingTitle"
                @update:dislike="emit('update:dislike')"
                @update:like="emit('update:like')"
                @delete="handleDelete"
                @update-description="handleUpdateDescription"
                @save-title="saveTitle"
                class="col-span-1"
            />
        </div>
    </div>
</template>
