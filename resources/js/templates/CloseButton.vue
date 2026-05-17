<script setup>
import { computed } from 'vue';

const props = defineProps({
    border: { type: Boolean, default: false },
    borderColor: { type: String, default: '#555' },
    backgroundColor: { type: String, default: '#3F3F3F' },
    xColor: { type: String, default: '#AAA' },
    diameter: { type: Number, default: 8 }
});

const emit = defineEmits(['close']);

const containerStyle = computed(() => {
    return {
        '--size': `${props.diameter * 0.25}rem`,
        backgroundColor: props.backgroundColor,
        color: props.xColor,
        border: props.border ? `1px solid ${props.borderColor}` : 'none'
    };
});

const handleClick = () => {
    emit('close');
};
</script>

<template>
    <div
        :style="containerStyle"
        class="w-[var(--size)] h-[var(--size)]rounded-full flex items-center justify-center select-none hover-pop"
        @click="handleClick"
    >
        ✕
    </div>
</template>
