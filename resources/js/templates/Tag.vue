<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
    content: {type: String, default: ''},
    isToggleable: {type: Boolean, default: false},
    mutedClasses: {type: String, default: 'opacity-50 greyscale'},
})

const active = ref(true);

const colorStyle = computed(() => {
    const subject = props.content.toLowerCase();

    switch (subject) {
        case 'wirtschaft':
            return 'text-[#633806] bg-[#FAEEDA]';
        case 'informatik':
            return 'text-[#155E75] bg-[#CFFAFE]';
        case 'mathe':
            return 'text-[#3C3489] bg-[#EEEDFE]';
        case 'maschinenbau':
            return 'text-[#7C2D12] bg-[#FEE2D5]';
        default:
            return 'bg-gray-200 text-gray-800';
    }
});

// Helper function for Uppercase first letter
const formattedContent = computed(() => {
    if (!props.content) return '';
    return props.content.charAt(0).toUpperCase() + props.content.slice(1);
});

</script>

<template>
    <div
        @click="isToggleable ? active = !active : null"
        :class="[
            'flex justify-center items-center rounded select-none transition-all text-center',
            isToggleable ? 'cursor-pointer' : '',
            (isToggleable && !active) ? mutedClasses : colorStyle
        ]"
    >
        {{ formattedContent }}
    </div>
</template>

<style scoped>

</style>
