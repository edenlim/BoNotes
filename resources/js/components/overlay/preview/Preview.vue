<script setup>
import Tag from '../../../templates/Tag.vue';

const props = defineProps({
    // Expecting an array of strings from your Laravel backend
    tags: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <Tag
            v-for="(tag, index) in tags"
            :key="index"
            :content="tag.toLowerCase()"
            class="px-2 py-1 text-card-tags font-medium"
        />
    </div>
</template>
