<script setup>
import {watch, onBeforeUnmount} from "vue";
const props = defineProps({
    show: Boolean
});

const emit = defineEmits(['close']);
watch(() => props.show, (isModalOpen) => {
    if (isModalOpen) {
        document.body.classList.add('overflow-hidden-scroll-lock');
    } else {
        document.body.classList.remove('overflow-hidden-scroll-lock');
    }
}, { immediate: true });

onBeforeUnmount(() => {
    document.body.classList.remove('overflow-hidden-scroll-lock');
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300 ease-in-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300 ease-in-out"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="props.show"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                @click="emit('close')"
            >
                <div
                    @click.stop
                    class="no-scrollbar bg-white rounded-lg shadow-xl max-w-[95vw] max-h-[90vh] overflow-y-auto relative"
                >
                    <slot />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
</style>
