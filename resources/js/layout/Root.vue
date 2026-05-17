<script setup>
import Card from '../components/card/Card.vue'; // Import your existing Rating component
import Navbar from "../components/navbar/Navbar.vue";
import Upload from "../components/upload/Upload.vue";
import {reactive} from "vue";
console.log("test !!")
const responseData = reactive( [{
    title: "Testing.pdf",
    fileType: ".pdf",
    tags: [],
    userVote: null,
    noOfDislikes: 1,
    noOfLikes: 0,
    author: "Tommy Höller",
    uploadTime: "10. Dec 2025",
    pageLength: 1,
    description: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.",
},{
    title: "Test.pdf",
    fileType: ".pdf",
    tags: [],
    userVote: null,
    noOfDislikes: 0,
    noOfLikes: 1,
    author: "Max Walter",
    uploadTime: "10. Apr 2025",
    pageLength: 3,
    description: "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat.",
},{
    title: "IT-woche1.txt",
    fileType: ".txt",
    tags: ['mathe', 'informatik'],
    userVote: 'dislike',
    noOfDislikes: 23,
    noOfLikes: 39,
    author: "Karl Gunterman",
    uploadTime: "15. Dec 2012",
    pageLength: 4,
    description: "At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.",
},{
    title: "Business-IT.txt",
    fileType: ".txt",
    tags: ['wirtschaft', 'informatik'],
    userVote: 'like',
    noOfDislikes: 3,
    noOfLikes: 369,
    author: "Jonas Berg",
    uploadTime: "29. Apr 2022",
    pageLength: 5,
    description: "Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.",
}])
</script>

<template>
    <Navbar/>
    <div class="p-4 flex justify-space-between gap-[2rem]">
        <Card
            v-for="(data, index) in responseData"
            :key="index"
            :data="data"
        />

    </div>
    <Upload/>

</template>

<style scoped>

</style>
