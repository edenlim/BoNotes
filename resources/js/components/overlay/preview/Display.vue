<script setup>
const props = defineProps({
    fileType: {type: String, default: '.txt'}
})
</script>

<template>
    <div class="text-white ">
        <div
            class="flex h-[50vh] justify-center items-center text-black"
            :class="fileType === '.pdf' ? 'bg-pdf-data' : 'bg-txt-data'"
        >
            PAGE 1
        </div>
        <div class="flex flex-row items-center justify-center gap-4 py-4 bg-[#323232] border-[#202020] border-r">
            <button class="preview-button hover-pop">prev</button>
            <p>Page</p>
            <button class="preview-button hover-pop">next</button>

        </div>
    </div>
</template>

<style scoped>

</style>
