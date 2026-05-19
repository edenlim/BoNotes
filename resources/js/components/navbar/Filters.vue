<script setup>
import {ref} from 'vue';
import Filter from "../../templates/Filter.vue";

const labels = ['Alle', 'Mathe', 'Informatik', 'Wirtschaft', 'Maschinenbau'];
// Creates a subject that the template can observe. This is our activeFilter array.
const activeFilters = defineModel('activeFilter', { type: Array, required: true });
const dropdownShow = ref(false);

const handleToggle = (clickedLabel) => {
    /*
    * 1. If Alle is clicked, only Alle will be toggled active.
    * 2. Else, check the Filter is part of activeFilter array
    *       - If the Filter is part of it, splice out of the array
    *       - Else push it into the array, and remove 'Alle'
    * 3. We create a temporary array variable (allSubjects) that contains every Tag except Alle
    * 4. We check that every element in allSubjects exists in activeFilter as well. If true, then all subjects are toggled on.
    * 5. If nothing is selected OR every subject is selected, reset the array to simply Alle.
    */
    if (clickedLabel === 'Alle') {
        activeFilters.value = ['Alle'];
        return;
    }
    let current = [...activeFilters.value];

    const index = current.indexOf(clickedLabel);
    if (index > -1) {
        current.splice(index, 1);
    } else {
        current.push(clickedLabel);
        current = current.filter(item => item !== 'Alle');
    }

    const allSubjects = labels.filter(label => label !== 'Alle');
    const allSpecificSelected = allSubjects.every(label => current.includes(label));

    if (current.length === 0 || allSpecificSelected) {
        activeFilters.value = ['Alle'];
    } else {
        activeFilters.value = current;
    }
};

const toggleDropdown = () =>{
    dropdownShow.value = !dropdownShow.value;
}
</script>

<template>
    <div >
        <div class="hidden lg:flex lg:flex-row lg:gap-4 lg:items-center lg:justify-center lg:h-full">
            <Filter
                v-for="label in labels"
                :key="label"
                :label="label"
                :is-active="activeFilters.includes(label)"
                @toggle="handleToggle(label)"
            />
        </div>
        <div class="lg:hidden">
            <div class="">
                <button
                    class="w-full select-none border-2 rounded-xl hover-pop p-2 dropdown relative"
                    @click="toggleDropdown"
                >
                    Tags

                </button>
                <div
                    class="translate-y-[0.7rem] -translate-x-1/6 flex-col absolute bg-white gap-2 pb-4 px-4 rounded-b-xl shadow-[0_4px_6px_-2px_rgba(0,0,0,0.1)] z-99"
                    :class="dropdownShow ? 'flex' : 'hidden'"
                >
                    <Filter
                        v-for="label in labels"
                        :key="label"
                        :label="label"
                        :is-active="activeFilters.includes(label)"
                        @toggle="handleToggle(label)"
                    />
                </div>

            </div>
        </div>
    </div>
</template>
