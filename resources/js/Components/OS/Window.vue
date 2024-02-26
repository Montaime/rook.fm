<script setup>
import {onMounted, ref} from 'vue'
import { useDraggable } from '@vueuse/core'

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
const z = ref(1)

const { x, y, style } = useDraggable(el, {
    initialValue: { x: props.x, y: props.y}
})

const resize = ref(null);

onMounted(() => {
    resize.value.style.height = resize.value.clientHeight + 'px';
});

const window = defineModel()

const visible = ref(props.visible)
</script>
<template>
    <div @mousedown="z = zHighest + 1" :style="style + 'z-index:' + z + ';'" class="window" :class="{'invisible': window.visibility !== 2}">
        <div ref="el" class="header flex space-x-2 p-1 justify-between select-none">
            <span class="text-sm px-1">{{ title }}</span>
            <div class="flex space-x-2">
                <svg @click="window.visibility = 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                </svg>
                <svg v-if="false" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M2 4.25A2.25 2.25 0 0 1 4.25 2h6.5A2.25 2.25 0 0 1 13 4.25V5.5H9.25A3.75 3.75 0 0 0 5.5 9.25V13H4.25A2.25 2.25 0 0 1 2 10.75v-6.5Z" />
                    <path d="M9.25 7A2.25 2.25 0 0 0 7 9.25v6.5A2.25 2.25 0 0 0 9.25 18h6.5A2.25 2.25 0 0 0 18 15.75v-6.5A2.25 2.25 0 0 0 15.75 7h-6.5Z" />
                </svg>
                <svg @click="window.visibility = 0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                </svg>
            </div>
        </div>
        <div ref="resize" :style="'width:500px; min-width:200px; min-height:50px;'" class="container relative flex flex-col space-y-2 p-2 grow" :class="containerClass"><slot/></div>
    </div>
</template>
