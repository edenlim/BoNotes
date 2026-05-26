<script setup>
import { ref } from 'vue';

const count = ref(0);

function increment() {
    count.value++;
}
</script>

<template>
    <div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-lg flex flex-col items-center space-y-4">
        <p class="text-slate-500 text-lg">
            Current Total: <span class="font-bold text-slate-900">{{ count }}</span>
        </p>

        <button
            @click="count++"
            class="px-4 py-1 text-sm text-green-600 font-semibold rounded-full border border-green-200 hover:text-white hover:bg-green-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2"
        >
            Add +1
        </button>
    </div>

</template>

<style scoped>

</style>
