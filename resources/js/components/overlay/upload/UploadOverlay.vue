<script setup>
import CloseButton from "../../../templates/CloseButton.vue";
import Tag from "../../../templates/Tag.vue";

const emit = defineEmits(['close']);
</script>

<template>
    <div class="bg-white w-[28rem] max-w-[90vw] rounded-2xl shadow-lg">
        <div class="flex items-center justify-between px-6 pt-5">
            <h2 class="text-lg font-semibold text-primary-text">Notiz hochladen</h2>
            <CloseButton @close="emit('close')" />
        </div>

        <form class="px-6 pb-6 pt-3" @submit.prevent>
            <div class="border border-dashed border-gray-300 rounded-xl p-6 text-center">
                <div class="text-sm font-medium text-primary-text">PDF oder TXT ablegen</div>
                <div class="text-xs text-secondary-text mt-1">.pdf und .txt werden unterstuetzt</div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-primary-text mb-1" for="upload-title">Titel</label>
                <input
                    id="upload-title"
                    type="text"
                    placeholder="z.B. Mathe Zusammenfassung"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                />
            </div>

            <div class="mt-5">
                <label class="block text-sm font-medium text-primary-text mb-2">Labels</label>
                <div class="flex flex-wrap gap-2">
                    <Tag
                        content="mathe"
                        isToggleable
                        mutedClasses="ring-1 ring-gray-300 text-gray-500 bg-white"
                        class="px-3 py-1 text-xs font-medium rounded-full"
                    />
                    <Tag
                        content="informatik"
                        isToggleable
                        mutedClasses="ring-1 ring-gray-300 text-gray-500 bg-white"
                        class="px-3 py-1 text-xs font-medium rounded-full"
                    />
                    <Tag
                        content="wirtschaft"
                        isToggleable
                        mutedClasses="ring-1 ring-gray-300 text-gray-500 bg-white"
                        class="px-3 py-1 text-xs font-medium rounded-full"
                    />
                    <Tag
                        content="maschinenbau"
                        isToggleable
                        mutedClasses="ring-1 ring-gray-300 text-gray-500 bg-white"
                        class="px-3 py-1 text-xs font-medium rounded-full"
                    />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    class="px-4 py-2 text-sm rounded-lg border border-gray-300 text-secondary-text cursor-pointer"
                    @click="emit('close')"
                >
                    Abbrechen
                </button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-primary text-white cursor-pointer">
                    Hochladen
                </button>
            </div>
        </form>
    </div>
</template>

<style scoped>

</style>
