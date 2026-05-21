<script setup>
import {watch, onBeforeUnmount} from "vue";
// Props to control visibility
const props = defineProps({
    show: Boolean
});

// Define the event to tell the parent to close
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
        <Transition name="fade">
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
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
