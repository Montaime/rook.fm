<script setup>
import Window from "../Components/OS/Window.vue";
import {ref} from "vue";
import {$$$, cMap} from "../util.js";
import Taskbar from "../Components/Taskbar.vue";

const clicked = ref(false);
</script>
<template>
    <div :class="$$$.theme.style">
        <div id="root" class="flex flex-col h-screen w-screen overflow-hidden bg-cover" :style="`background-image: url('${$$$.theme.wallpaper}')`">
            <div class="invisible grow overflow-hidden">
                <div class="h-full text-white w-full grid grid-flow-row grid-rows-12 gap-4 p-0.5">
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10 border border-black bg-cyan-400/50 border-dashed">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">License Key.txt</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">Credits.txt</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">How to use.ppt</span>
                    </div>
                    <div class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10">
                        <svg class="fill-white stroke-black w-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                        <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">songlist.html</span>
                    </div>
                </div>
            </div>
            <Taskbar/>
        </div>
        <div v-if="!clicked" @click="clicked = true" class="flex flex-col cursor-pointer items-center justify-center absolute inset-0 backdrop-blur-md z-10">
            <div class="cursor-pointer rounded-full p-8 bg-white/50 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
            </div>
            <p class="text-4xl cursor-pointer font-bold mt-4">Click Anywhere To Unlock</p>
        </div>
        <div v-if="clicked" @mousedown="$$$.desktop.zHighest++" class="relative z-0">
            <Window v-for="(w, key) in $$$.windows" v-model="$$$.windows[key]" :z-highest="$$$.desktop.zHighest" :x="Math.floor(Math.random() * 450) + 50" :y="Math.floor(Math.random() * 450) + 50" :container-class="w.style" :title="key">
                <component :is="cMap[key]"/>
            </Window>
        </div>
    </div>
</template>
