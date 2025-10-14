<script setup>
import {onMounted, ref} from 'vue'
import { useDraggable } from '@vueuse/core'
import {$$$, WindowState} from '@/util.js'

const props = defineProps({
    title: String,
    decoration: Number,
    id: String,
    containerClass: String,
    x: Number,
    y: Number,
    zHighest: Number
})

const el = ref(null)

const { x, y, style } = useDraggable(el, {
    initialValue: { x: props.x, y: props.y},

})

const resize = ref(null);

onMounted(() => {
    //if ($$$.windows[props.title].value.resizable) resize.value.style.height = resize.value.clientHeight + 'px';
});

const window = defineModel()

const visible = ref(props.visible)
</script>
<template>
    <div @mousedown="$$$.windows[title].z = ++$$$.desktop.zHighest" :style="style + 'z-index:' + $$$.windows[title].z + ';'" class="window-container flex flex-col md:fixed" :class="{'md:hidden': window.visibility !== 2}">
        <div class="window relative">
<!--            <div ref="dTL" :style="dTLstyle" class="-left-1 -top-1 absolute cursor-nwse-resize p-2 bg-blue-500 z-20"></div>-->
<!--            <div ref="dBL" class="absolute -left-1 -bottom-1 cursor-nesw-resize p-2 bg-red-500 z-20"></div>-->
<!--            <div ref="dTP" class="absolute -right-1 -top-1 cursor-nesw-resize p-2 bg-red-500 z-20"></div>-->
<!--            <div ref="dBR" class="absolute -right-1 -bottom-1 cursor-nwse-resize p-2 bg-red-500 z-20"></div>-->
<!--            <div ref="dT" class="absolute -top-1 cursor-ns-resize w-full py-1 bg-green-500"></div>-->
<!--            <div ref="dB" class="absolute -bottom-1 cursor-ns-resize w-full py-1 bg-green-500"></div>-->
<!--            <div ref="dL" class="absolute -left-1 cursor-ew-resize h-full px-1 bg-green-500"></div>-->
<!--            <div ref="dR" class="absolute -right-1 cursor-ew-resize h-full px-1 bg-green-500"></div>-->
            <div class="header flex space-x-2 justify-between select-none">
                <div ref="el" class="flex space-x-1.5 px-2 items-center grow">
                    <span class="text-sm py-1">{{ title }}</span>
                    <svg v-if="['Radio', 'Livestream'].includes(title)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                        <path d="M10.5 3.75a.75.75 0 0 0-1.264-.546L5.203 7H2.667a.75.75 0 0 0-.7.48A6.985 6.985 0 0 0 1.5 10c0 .887.165 1.737.468 2.52.111.29.39.48.7.48h2.535l4.033 3.796a.75.75 0 0 0 1.264-.546V3.75ZM16.45 5.05a.75.75 0 0 0-1.06 1.061 5.5 5.5 0 0 1 0 7.778.75.75 0 0 0 1.06 1.06 7 7 0 0 0 0-9.899Z" />
                        <path d="M14.329 7.172a.75.75 0 0 0-1.061 1.06 2.5 2.5 0 0 1 0 3.536.75.75 0 0 0 1.06 1.06 4 4 0 0 0 0-5.656Z" />
                    </svg>
                </div>
                <div class="hidden md:flex">
                    <div class="p-1 hover:bg-white/50">
                        <svg @click="window.visibility = 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div v-if="false" class="p-1 hover:bg-white/50">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M2 4.25A2.25 2.25 0 0 1 4.25 2h6.5A2.25 2.25 0 0 1 13 4.25V5.5H9.25A3.75 3.75 0 0 0 5.5 9.25V13H4.25A2.25 2.25 0 0 1 2 10.75v-6.5Z" />
                            <path d="M9.25 7A2.25 2.25 0 0 0 7 9.25v6.5A2.25 2.25 0 0 0 9.25 18h6.5A2.25 2.25 0 0 0 18 15.75v-6.5A2.25 2.25 0 0 0 15.75 7h-6.5Z" />
                        </svg>
                    </div>
                    <div class="p-1 hover:bg-white/50">
                        <svg @click="window.visibility = 0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                        </svg>
                    </div>
                </div>
                <div class="md:hidden p-1 hover:bg-white/50">
                    <svg @click="window.visibility = (window.visibility !== WindowState.Open ? WindowState.Open : WindowState.Minimized)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" :class="{'rotate-90': window.visibility !== 2}">
                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div ref="resize" style="min-width:200px; min-height:50px;" class="container relative flex flex-col space-y-2 p-2 grow w-full md:w-[500px]" :class="{containerClass: true, 'hidden': window.visibility !== 2}"><slot/></div>
        </div>
    </div>
</template>
