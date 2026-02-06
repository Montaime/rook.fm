<script setup>
import poolbg from "../../assets/wallpapers/pool.jpg"
import vol3 from "../../assets/wallpapers/vol3.jpg"
import marble from "../../assets/wallpapers/mere.jpg"
import hoo from "../../assets/wallpapers/hoosteen.jpg"
import rendez from "../../assets/wallpapers/rendezvous.jpg"
import nror from "../../assets/wallpapers/NROR.jpg"
import krim from "../../assets/wallpapers/krim.jpg"
import discos from "../../assets/wallpapers/discos.jpg"
import rookfm from "../../assets/wallpapers/rookfm.jpg"
import studios from "../../assets/wallpapers/studios.jpg"
import vision from "../../assets/wallpapers/vision.jpg"
import virtues from "../../assets/wallpapers/virtues.jpg"
import prophecy from "../../assets/wallpapers/prophecy.jpg"
import soulseek from "../../assets/wallpapers/soulseek.jpg"
import soulcd from "../../assets/wallpapers/soulcd.jpg"
import {$$$} from "../util.js";
import {useStorage} from "@vueuse/core";
import {computed, ref} from "vue";

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
    vol3,
    poolbg,
    marble,
    // hoo,
    rendez,
    nror,
    krim,
    // discos,
    // rookfm,
    // studios,
    prophecy,
    vision,
    virtues,
    soulseek,
    soulcd
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
    // 'hoo': {
    //     name: 'Whosteen?',
    //     description: 'Mr. Hoosteen!',
    //     dark: true,
    //     wallpaper: hoo,
    // },
    // 'virtual': {
    //     name: 'Virtual World',
    //     description: 'See reality through fibre optics',
    //     dark: true,
    //     wallpaper: rendez,
    // },
    // 'disco': {
    //     name: 'Discohol',
    //     description: 'It goes down smooth',
    //     dark: false,
    //     wallpaper: nror,
    // },
    // 'krim': {
    //     name: 'Dr. Plekter too ig',
    //     description: 'why not idk',
    //     dark: true,
    //     wallpaper: krim,
    // },
    'soul': {
        name: 'Soulseeker',
        description: 'no mp3 flacs!!',
        dark: false,
        wallpaper: soulseek,
    },
};

const tab = ref(0)

const lightbox = ref(false);

const windows = computed(() => lightbox.value ? Object.keys(themes) : []);
</script>
<template>
    <div class="flex">
        <nav class="flex flex-col ui-nav">
            <div @click="tab = 0" class="px-4 py-2 cursor-pointer select-none" :class="{'ui-navlink-active': tab === 0}">Theme Bundles</div>
            <div @click="tab = 1" class="px-4 py-2 cursor-pointer select-none" :class="{'ui-navlink-active': tab === 1}">Wallpapers</div>
            <div @click="lightbox = true" class="px-4 py-2 cursor-pointer select-none">Styles</div>
        </nav>
        <div class="p-2">
            <div v-if="tab === 0">
                <h1 class="font-bold text-4xl">Theme Bundles</h1>
                <p>Nicely paired wallpaper and style combinations</p>
                <div v-for="(theme, key) in themes" @click="setTheme(key, theme.wallpaper)" class="cursor-pointer flex space-x-2 py-1">
                    <div class="aspect-video rounded bg-white h-14 bg-cover" :style="`background-image: url('${theme.wallpaper}');`"></div>
                    <div class="flex flex-col">
                        <h1 class="font-bold text-2xl">{{ theme.name }}</h1>
                        <p>{{ theme.description }}</p>
                    </div>
                </div>
            </div>
            <div v-if="tab === 1" class="grid grid-cols-2 md:grid-cols-3 gap-2">
                <div v-for="wallpaper in wallpapers" @click="setWallpaper(wallpaper)" class="cursor-pointer flex space-x-2">
                    <div class="aspect-video p-4 rounded bg-white h-14 bg-cover" :style="`background-image: url('${wallpaper}');`"></div>
                </div>
            </div>
        </div>
        <teleport to="#teleports">
            <div @click="lightbox = false" v-if="lightbox" class="bg-black/75 absolute inset-0 flex flex-col items-center justify-center z-50 backdrop-blur-sm">
                <h2 class="text-white font-bold text-4xl mb-4">Select a Style</h2>
                <transition-group enter-from-class="opacity-0 -translate-y-6 blur-lg mx-0" enter-to-class="opacity-100 translate-y-0 blur-0 !mx-4" appear name="fade" tag="div" class="grid grid-cols-2 md:flex relative transition-group">
                    <div v-for="(theme, i) in windows" :key="theme" @click="setStyle(theme)" :class="theme" :style="`transition-delay: ${25 * i}ms`" class="cursor-pointer">
                        <div class="window-container flex flex-col !backdrop-blur-0">
                            <div class="window relative">
                                <div class="header flex justify-between select-none">
                                    <div class="flex space-x-1.5 px-2 items-center grow">
                                        <span class="text-sm py-1">{{ theme }}</span>
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
                                <div class="container max-w-full min-w-full md:min-w-0 py-10 px-20"></div>
                            </div>
                        </div>
                    </div>
                </transition-group>
            </div>
        </teleport>
    </div>
</template>
<style>
.fade-enter-active {
    transition: opacity 300ms ease-out, transform 400ms ease-out, filter 300ms ease-out, margin 400ms ease-out;
}

.transition-group > div:not(.fade-enter-active) {
    @apply mx-4;
}
</style>
