<script setup>
import {ref, computed} from 'vue';
const props = defineProps({
    fileType: {type: String, default: '.txt'},
    page_length: {type: Number, required: true}
})
const currentPage = ref(1);
const singlePage = ref(props.page_length===1);

const status = computed(() => {
    if (props.page_length <= 1) return 'singlePage';
    if (currentPage.value === 1) return 'firstPage';
    if (currentPage.value === props.page_length) return 'lastPage';
    return null;
});

const handlePrev = () => {
    if(status.value === 'singlePage') return;
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};

const handleNext = () => {
    if(status.value === 'singlePage') return;
    if (currentPage.value < props.page_length) {
        currentPage.value++;
    }
};
</script>

<template>
    <div class="text-white ">
        <div
            class="flex h-[50vh] md:h-[50vh] justify-center items-center text-black"
            :class="fileType === '.pdf' ? 'bg-pdf-data' : 'bg-txt-data'"
        >
            PAGE {{currentPage}}
        </div>
        <div v-if="status != 'singlePage'" class="flex flex-row items-center justify-center gap-4 py-4 bg-[#323232] border-[#202020] border-r">
                <button
                    class="preview-button"
                    :class="status === 'firstPage' || status === 'singlePage' ? 'text-[#555]' : 'hover-pop'"
                    @click="handlePrev"
                >
                    prev
                </button>
                <p>Seite {{currentPage}} von {{page_length}}</p>
                <button
                    class="preview-button "
                    :class="status === 'lastPage' || status === 'singlePage' ? 'text-[#555]' : 'hover-pop'"
                    @click="handleNext"

                >
                    next
                </button>
        </div>
        <div v-else class="flex flex-row items-center justify-center gap-4 py-8 bg-[#323232] border-[#202020] border-r">
            <p>Eine Seite</p>
        </div>
    </div>
</template>

<style scoped>

</style>
