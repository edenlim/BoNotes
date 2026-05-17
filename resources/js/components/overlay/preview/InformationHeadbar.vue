<script setup>

import CloseButton from "../../../templates/CloseButton.vue";
import Tag from "../../../templates/Tag.vue";
const props = defineProps({
    data: {
        type: Object,
        required: true,
        default: () => ({ tags: [], title: '' })
    }
});
const emit = defineEmits(['close']);

</script>

<template>
    <div class="flex px-5 py-4 justify-between mx-auto">
        <h1 class="text-white text-lg font-bold">{{ data.title }}</h1>
        <div class="flex flex-row gap-[0.75rem]">
            <Tag
                v-for="(tag, index) in data.tags"
                :key="index"
                :content="tag.toLowerCase()"
                class="px-2 py-1 text-card-tags font-medium"
            />
            <CloseButton @close="emit('close')" />
        </div>
    </div>
</template>

<style scoped>

</style>
