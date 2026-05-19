<script setup>
import Filter from "../../templates/Filter.vue";

const labels = ['Alle', 'Mathe', 'Informatik', 'Wirtschaft', 'Maschinenbau'];
// Creates a subject that the template can observe. This is our activeFilter array.
const activeFilters = defineModel('activeFilter', { type: Array, required: true });
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
</script>

<template>
    <div class="flex flex-row gap-4 items-center justify-center h-full">
        <Filter
            v-for="label in labels"
            :key="label"
            :label="label"
            :is-active="activeFilters.includes(label)"
            @toggle="handleToggle(label)"
        />
    </div>
</template>
