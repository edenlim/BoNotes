<script setup>
import {ref} from 'vue';
const props = defineProps({
    label: {type: String, default: null}
})

const active = ref(false);

const toggle = () => active.value = !active.value;

</script>

<template>
    <div
        class="text-center min-w-15 p-2 select-none border-2 rounded-xl cursor-pointer transition-all duration-200 hover:scale-105"
        :class="{
            'border-secondary-text text-secondary-text' : !active,
            'border-tertiary-text text-tertiary-text bg-primary ' : active
        }"
        @click="toggle"
    >
        {{label}}
    </div>
</template>

<style scoped>

</style>
