<script setup>
import logo_black from './../../assets/logo_banner_black.png'
import logo from './../../assets/logo_banner.png'
import mark from '@/../assets/click_mark.mp3'
import rook from '@/../assets/click_rook.mp3'
import f from '@/../assets/click_f.mp3'
import m from '@/../assets/click_m.mp3'
import mmm from '@/../assets/click_m_long.mp3'
import {onMounted, ref} from "vue";

const audio = ref({})

onMounted(() => {
    audio.value = {
        mark: new Audio(mark),
        rook: new Audio(rook),
        f: new Audio(f),
        m: new Audio(m),
        mmm: new Audio(mmm),
    }
})

const clicked = ref([]);
const glow = ref(false);

const play = (sound) => {
    if (!audio.value.hasOwnProperty(sound) || !audio.value[sound] instanceof Audio) return;

    clicked.value.push(sound);
    if (clicked.value.length > 3) clicked.value.shift();
    let full = clicked.value[0] === 'rook' && clicked.value[1] === 'f' && clicked.value[2] === 'm';

    if (full) {
        audio.value['mmm'].currentTime = 0;
        audio.value['mmm'].play();
    } else {
        audio.value[sound].currentTime = 0;
        audio.value[sound].play();
    }

    glow.value = full;
}
</script>
<template>
    <div class="relative">
        <img class="object-contain h-32 relative z-10" :src="logo_black" alt="" />
        <div @transitionend="glow = false" class="glow absolute top-0 left-0 w-full" :class="{'glow-active': glow}">
            <div class="h-32" :style="`mask-image: url(${logo})`"></div>
        </div>
        <div class="absolute inset-0 z-20">
            <div @click="play('mark')" class="cursor-pointer absolute left-0 top-0 h-32 w-40"></div>
            <div @click="play('rook')" class="cursor-pointer absolute left-40 top-8 h-16 w-44"></div>
            <div @click="play('f')" class="cursor-pointer absolute right-16 top-8 h-16 w-8"></div>
            <div @click="play('m')" class="cursor-pointer absolute right-0 top-12 h-12 w-16"></div>
        </div>
    </div>
</template>
<style scoped>
.glow {
    filter: blur(5px);
    opacity: 0;
    transition: opacity 4s ease-in, filter 3s ease-in;
    transition-delay: 250ms;
}

.glow > div {
    mask-size: contain;
    background-image: linear-gradient(
        45deg,
        #fb0094,
        #0000ff,
        #00ff00,
        #ffff00,
        #ff0000,
        #fb0094,
        #0000ff,
        #00ff00,
        #ffff00,
        #ff0000
    );
    background-size: 400%;
    animation: animate 20s linear infinite;
}

.glow-active {
    transition: opacity 500ms ease, filter 1s ease-out;
    opacity: 1;
    filter: blur(20px);
}

@keyframes animate {
    0% {
        background-position: 0 0;
    }
    50% {
        background-position: 300% 0;
    }
    100% {
        background-position: 0 0;
    }
}
</style>
