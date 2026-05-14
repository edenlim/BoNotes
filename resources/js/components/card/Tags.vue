<script setup>
import Tag from '../../templates/Tag.vue'; // Ensure the path is correct

const props = defineProps({
    // We expect an array of strings from Laravel
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
