<script setup>
import Card from '../components/card/Card.vue'; // Import your existing Rating component
import Navbar from "../components/navbar/Navbar.vue";
</script>

<template>
    <Navbar/>
    <div class="p-4">
        <div class="flex justify-space-between gap-[2rem]">
            <Card title="Test.pdf" file-type=".pdf" />

            <Card
                title="IT-woche1.txt"
                file-type=".txt"
                :tags="['mathe', 'informatik']"
                no-of-dislikes="23"
                no-of-likes="39"
            />

            <Card
                title="Business-IT.txt"
                file-type=".txt"
                :tags="['wirtschaft', 'informatik']"
                no-of-dislikes="3"
                no-of-likes="369"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
