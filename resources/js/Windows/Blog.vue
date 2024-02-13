<script setup>

const blog = [
    // {
    //     title: 'Radio Takeover on Friday!',
    //     content: 'Stay tuned to the radio this Friday, I\'ll be playing mix event along with the rest of...',
    //     tags: ['event']
    // }
];
</script>
<template>
    <div>
        <h2 class="font-bold text-2xl">Mere Notilde Club</h2>
    </div>
    <div class="border-t border-gray-400"></div>
    <div class="flex flex-col divide-y">
        <div v-for="post in blog" class="leading-0 pb-1">
            <h1 class="font-bold text-lg">{{ post.title }}</h1>
            <p>{{ post.content }} <span class="underline italic">Read More</span></p>
            <span v-for="tag in post.tags" class="rounded-full px-2 py-0.5 text-xs uppercase bg-red-500 text-white">{{ tag }}</span>
        </div>
        <div v-if="blog.length === 0">
            <p class="italic text-center opacity-75 font-bold">You're an early bird! This club has no updates yet.</p>
        </div>
    </div>
</template>
