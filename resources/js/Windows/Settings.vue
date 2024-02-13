<script setup>
import poolbg from "@/../assets/wesley-tingey-9-OCsKoyQlk-unsplash.jpg"
import vol3 from "@/../assets/vol3.png"
import marble from "@/../assets/mere.png"
</script>
<template>
    <div @click="activeTheme = vol3; uiTheme = 'brutal'" class="cursor-pointer flex space-x-2 py-2">
        <div class="aspect-video p-4 rounded bg-red-500 h-12 bg-cover" :style="`background-image: url('${vol3}');`"></div>
        <div class="flex flex-col">
            <h1 class="font-bold text-2xl">Vol. 3</h1>
            <p>Welcome aboard!</p>
        </div>
    </div>
    <div @click="activeTheme = poolbg; uiTheme = 'glass'" class="cursor-pointer flex space-x-2 py-2">
        <div class="aspect-video p-4 rounded bg-red-500 h-12 bg-cover" :style="`background-image: url('${poolbg}');`"></div>
        <div class="flex flex-col">
            <h1 class="font-bold text-2xl">Crystal Clear</h1>
            <p>Come take a dip...</p>
        </div>
    </div>
    <div @click="activeTheme = marble; uiTheme = 'neu'" class="cursor-pointer flex space-x-2 py-2">
        <div class="aspect-video p-4 rounded bg-red-500 h-12 bg-cover" :style="`background-image: url('${marble}');`"></div>
        <div class="flex flex-col">
            <h1 class="font-bold text-2xl">C'mere</h1>
            <p>You gotta check this out!</p>
        </div>
    </div>
</template>
