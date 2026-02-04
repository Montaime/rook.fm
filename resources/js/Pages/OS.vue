<script setup>
import Window from "../Components/OS/Window.vue";
import {ref} from "vue";
import {$$$} from "../util.js";
import Taskbar from "../Components/Taskbar.vue";

const smallSizeClicked = ref(false);
</script>
<template>
    <div :class="{[$$$.theme.style]: true, 'pointer-events-none select-none': $$$.desktop.transforming}">
        <div id="root" class="flex flex-col overflow-hidden bg-cover bg-center" :style="`background-image: url('${$$$.theme.wallpaper}')`" style="height: 100dvh; width: 100dvw;">
            <div id="bounding" class="grow relative">
                <div v-if="false" class="absolute inset-0 text-white grid grid-flow-row grid-rows-12 gap-4 p-0.5">
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10 border border-black bg-cyan-400/50 border-dashed">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #000 0 0 3px">keygen.exe</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">about.txt</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">blog.ppt</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">welcome.html</span>
                    </div>
                </div>
                <div class="absolute z-0 inset-0 y space-y-4 md:space-y-0 overflow-y-auto md:overflow-hidden p-2 md:p-0 pointer-events-none">
                    <div :style="`left:${$$$.snap.x}px;top:${$$$.snap.y}px;height:${$$$.snap.height}px;width:${$$$.snap.width}px;`" class="absolute opacity-0 hidden md:block" :class="{'transition-all opacity-100': $$$.snap.width > 0 || $$$.snap.height > 0}">
                        <div class="absolute inset-2 border border-neutral-200/50 bg-neutral-300/20"></div>
                    </div>
                    <Window class="pointer-events-auto" v-for="(w, key) in $$$.windows" v-model="$$$.windows[key]"/>
                </div>
            </div>
            <Taskbar/>
        </div>
        <div v-if="!smallSizeClicked" @click="smallSizeClicked = true" class="flex flex-col md:hidden items-center justify-center text-center absolute inset-0 backdrop-blur-lg z-50">
            <div class="cursor-pointer rounded-full p-8 bg-white/50 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                </svg>
            </div>
            <p class="text-4xl cursor-pointer bg-white/50 p-4 rounded-md font-bold mt-4">Hey!</p>
            <p class="text-2xl cursor-pointer p-4 bg-white/50 rounded-md m-4">rook.fm mobile is VERY unfinished & far more stable on a desktop web browser (Chrome/Firefox/Safari). Things WILL be a little broken (as a treat)<br/>🛠️  Please report anything weird to help us out 💖</p>
            <button class="rounded-md bg-white/50 px-4 py-2">I Understand</button>
        </div>
    </div>
</template>
