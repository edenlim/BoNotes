<script setup>
import Tag from '../../../templates/Tag.vue';
import CloseButton from "../../../templates/CloseButton.vue";

const props = defineProps({
    tags: { type: Array, default: () => [] },
    title: { type: String, default: "No Title" }
});

const emit = defineEmits(['close']);
</script>

<template>
    <div class="flex px-5 py-4 justify-between w-[50vw] mx-auto bg-preview-bg">

        <h1 class="text-white text-lg font-bold">{{ title }}</h1>
        <div class="flex flex-row gap-[0.75rem]">
            <Tag
                v-for="(tag, index) in tags"
                :key="index"
                :content="tag.toLowerCase()"
                class="px-2 py-1 text-card-tags font-medium"
            />
            <CloseButton @close="emit('close')" />
        </div>
    </div>
</template>
