<script setup>
import poolbg from "../../assets/wallpapers/pool.jpg"
import vol3 from "../../assets/wallpapers/vol3.jpg"
import marble from "../../assets/wallpapers/mere.jpg"
import hoo from "../../assets/wallpapers/hoosteen.png"
import rendez from "../../assets/wallpapers/rendezvous.jpg"
import nror from "../../assets/wallpapers/NROR.jpg"
import krim from "../../assets/wallpapers/krim.jpg"
import discos from "../../assets/wallpapers/discos.jpg"
import rookfm from "../../assets/wallpapers/rookfm.jpg"
import studios from "../../assets/wallpapers/studios.jpg"
import {$$$} from "../util.js";
import {noop, useStorage} from "@vueuse/core";
import {ref} from "vue";
import Window from "@/Components/OS/Window.vue";

const theme = useStorage('rk_theme', 'glass')
const wallpaper = useStorage('rk_wallpaper', poolbg)

const setTheme = (css, img) => {
    setStyle(css);
    setWallpaper(img);
}

const setStyle = (css) => {
    theme.value = $$$.theme.style = css;
}

const setWallpaper = (img) => {
    wallpaper.value = $$$.theme.wallpaper = img;
}

const wallpapers = [
    vol3, poolbg, marble, hoo, rendez, nror, krim, discos, rookfm, studios
];

const themes = {
    'brutal': {
        name: 'Vol. 3',
        description: 'Welcome aboard!',
        dark: false,
        wallpaper: vol3,
    },
    'glass': {
        name: 'Crystal Clear',
        description: 'Come take a dip...',
        dark: false,
        wallpaper: poolbg,
    },
    'neu': {
        name: 'Mere Perfection',
        description: 'Accept nothing less',
        dark: false,
        wallpaper: marble,
    },
    'hoo': {
        name: 'Whosteen?',
        description: 'Mr. Hoosteen!',
        dark: true,
        wallpaper: hoo,
    },
    'virtual': {
        name: 'Virtual World',
        description: 'See reality through fibre optics',
        dark: true,
        wallpaper: rendez,
    },
    'disco': {
        name: 'Discohol',
        description: 'It goes down smooth',
        dark: false,
        wallpaper: nror,
    },
    'krim': {
        name: 'Dr. Plekter too ig',
        description: 'why not idk',
        dark: true,
        wallpaper: krim,
    },
};

const tab = ref(0)
</script>
<template>
    <select v-if="0" class="bg-neutral-800 rounded border-none px-2 py-1">
        <option>All</option>
        <option>Light</option>
        <option>Dark</option>
        <option>High Contrast</option>
    </select>
    <select v-model="tab" class="bg-neutral-800 rounded border-none px-2 py-1">
        <option :value="0">Themes</option>
        <option :value="1">Wallpapers Only</option>
        <option :value="2">Styles Only</option>
    </select>
    <div v-if="tab === 0">
        <div v-for="(theme, key) in themes" @click="setTheme(key, theme.wallpaper)" class="cursor-pointer flex space-x-2 py-2">
            <div class="aspect-video p-4 rounded bg-white h-12 bg-cover" :style="`background-image: url('${theme.wallpaper}');`"></div>
            <div class="flex flex-col">
                <h1 class="font-bold text-2xl">{{ theme.name }}</h1>
                <p>{{ theme.description }}</p>
            </div>
        </div>
    </div>
    <div v-if="tab === 1" class="grid grid-cols-3 gap-2">
        <div v-for="wallpaper in wallpapers" @click="setWallpaper(wallpaper)" class="cursor-pointer flex space-x-2 py-2">
            <div class="aspect-video p-4 rounded bg-white h-12 bg-cover" :style="`background-image: url('${wallpaper}');`"></div>
        </div>
    </div>
    <div v-if="tab === 2" class="grid grid-cols-3 gap-2">
        <div v-for="(theme, key) in themes" @click="setStyle(key)" class="border rounded-md p-4 bg-white/25" id="theme-reset" :data-theme-force="key">
            <div class="window-container flex flex-col">
                <div class="window relative">
                    <div class="header flex justify-between select-none">
                        <div class="flex space-x-1.5 px-2 items-center grow">
                            <span class="text-sm py-1">{{ key }}</span>
                        </div>
                        <div class="hidden md:flex">
                            <div class="p-1 hover:bg-white/50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex items-center p-1 hover:bg-white/50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 -scale-x-100">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V6a2.25 2.25 0 0 0-2.25-2.25H6A2.25 2.25 0 0 0 3.75 6v8.25A2.25 2.25 0 0 0 6 16.5h2.25m8.25-8.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-7.5A2.25 2.25 0 0 1 8.25 18v-1.5m8.25-8.25h-6a2.25 2.25 0 0 0-2.25 2.25v6" />
                                </svg>
                            </div>
                            <div class="p-1 hover:bg-white/50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="container max-w-full min-w-full md:min-w-0 h-16"></div>
                </div>
            </div>
        </div>
    </div>
</template>
