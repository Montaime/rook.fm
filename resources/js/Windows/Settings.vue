<script setup>
import poolbg from "../../assets/wallpapers/pool.jpg"
import vol3 from "../../assets/wallpapers/vol3.jpg"
import marble from "../../assets/wallpapers/mere.jpg"
import {$$$} from "../util.js";
import {useStorage} from "@vueuse/core";

const theme = useStorage('rk_theme', 'glass')
const wallpaper = useStorage('rk_wallpaper', poolbg)

const setTheme = (css, img) => {
    wallpaper.value = $$$.theme.wallpaper = img;
    theme.value = $$$.theme.style = css;
}
</script>
<template>
    <div @click="setTheme('brutal', vol3)" class="cursor-pointer flex space-x-2 py-2">
        <div class="aspect-video p-4 rounded bg-white h-12 bg-cover" :style="`background-image: url('${vol3}');`"></div>
        <div class="flex flex-col">
            <h1 class="font-bold text-2xl">Vol. 3</h1>
            <p>Welcome aboard!</p>
        </div>
    </div>
    <div @click="setTheme('glass', poolbg)" class="cursor-pointer flex space-x-2 py-2">
        <div class="aspect-video p-4 rounded bg-white h-12 bg-cover" :style="`background-image: url('${poolbg}');`"></div>
        <div class="flex flex-col">
            <h1 class="font-bold text-2xl">Crystal Clear</h1>
            <p>Come take a dip...</p>
        </div>
    </div>
    <div @click="setTheme('neu', marble)" class="cursor-pointer flex space-x-2 py-2">
        <div class="aspect-video p-4 rounded bg-white h-12 bg-cover" :style="`background-image: url('${marble}');`"></div>
        <div class="flex flex-col">
            <h1 class="font-bold text-2xl">C'mere</h1>
            <p>You gotta check this out!</p>
        </div>
    </div>
</template>
