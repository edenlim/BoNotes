<script setup>
import Card from '../components/card/Card.vue'; // Import your existing Rating component

</script>

<template>
    <div class="flex justify-space-between gap-[2rem]">
        <Card title="Test.pdf" file-type=".pdf" />

        <Card
            title="IT-woche1.txt"
            file-type=".txt"
            :tags="['mathe', 'informatik']"
        />

        <Card
            title="Business-IT.txt"
            file-type=".txt"
            :tags="['wirtschaft', 'informatik']"
        />
    </div>
    <div>hello</div>
</template>

<style scoped>

</style>
