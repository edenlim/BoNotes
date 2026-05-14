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
        class="p-2 border-2 rounded-xl  cursor-pointer "
        :class="{
            'border-secondary-text text-secondary-text' : !active,
            'border-tertiary-text text-tertiary-text bg-primary ' : active
        }"
        @click="toggle"
    >
        {{label}}: {{active}}
    </div>
</template>

<style scoped>

</style>
