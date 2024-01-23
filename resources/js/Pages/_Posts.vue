<script setup>
import Post from "@/Components/Post.vue";

const props = defineProps({
    posts: Object,
    club: Object
})

const year = new Date().getFullYear();
</script>
<template>
    <div class="flex flex-col items-center bg-gray-200 min-h-screen overflow-y-auto">
        <div class="flex space-x-4 w-full bg-gray-300 w-full max-w-2xl p-4">
            <div class="aspect-square p-8 rounded-full bg-gray-500"></div>
            <div class="flex flex-col">
                <div class="font-bold text-4xl">{{ club.name }}</div>
                <div>Chicago, IL</div>
            </div>
        </div>
        <div class="flex flex-col space-y-16 w-full max-w-2xl p-8 bg-gray-100">
            <Post v-for="post in posts" :post="post"/>
        </div>
        <div class="flex text-sm text-gray-400 justify-between w-full max-w-2xl px-2 py-1">
            <span>{{ club.name }} is hosted on <a href="#" class="underline">RookFM</a></span>
            <span>&copy; {{ year }}</span>
        </div>
    </div>
</template>
