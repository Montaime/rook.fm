<script setup>
import logo_black from './../../assets/logo_banner_black.png'
import {onBeforeMount, ref} from "vue";

const audio = ref({})

onBeforeMount(() => {
    const pages = import.meta.glob('./../../assets/jingle/*.mp3', {
        query: '?url',
        import: 'default',
        eager: true
    });

    for (let page in pages) audio.value[page.substring(page.lastIndexOf('/') + 1, page.length - 4)] = new Audio(pages[page]);
})

const clicked = ref([]);
const glow = ref(0);

const play = (sound) => {
    clicked.value.push(sound);

    let full = false;
    let progress = 0;
    const TARGET = ['intro', 'rook', 'f', 'm'];

    for (let i = 0; i < clicked.value.length; i++) {
        // We're follwing the correct sequence
        if (clicked.value[i] === TARGET[progress]) {
            full = true;
            progress++;
        } else if (clicked.value[i] === 'intro') {
            // Made a mistake, but this one was the start of the sequence
            progress = 1;
        } else {
            // Full blunder, reset progress
            full = false;
            progress = 0;
        }
    }

    // If we at least followed the 'rookfm' sequence right
    let end = clicked.value.join('').endsWith('rookfm');

    // If we did 'f' immediately following 'rook' include that k syllable
    if (sound === 'f' && clicked.value[clicked.value.length - 2] === 'rook') sound = 'kef';

    // Choose the right sound source
    sound = `${full ? 'full' : 'acapella'}_${sound}`;

    // If we're at the end of either valid sequence, extend the 'm'
    if (end) sound += 'mm';

    // Safety check
    if (!audio.value.hasOwnProperty(sound) || !audio.value[sound] instanceof Audio) {
        clicked.value.pop();
        return;
    } else if (clicked.value.length >= 5) {
        // Keep the array at a cycling length of 4
        clicked.value.shift();
    }

    // Play sound
    audio.value[sound].currentTime = 0;
    audio.value[sound].play();

    // Glow for either valid sequence
    if (end) glow.value = 1;
    if (full && end) glow.value = 2;
    if (!end && !full) glow.value = 0;
}
</script>
<template>
    <div class="relative">
        <img class="object-contain h-32 relative z-10" :src="logo_black" alt="" />
        <div @transitionend="glow = 0" class="glow rainbow absolute top-0 left-0 w-full" :class="{'glow-active': glow > 0}">
            <div class="h-32" :style="`mask-image: url(${logo_black})`"></div>
        </div>
        <teleport to="body">
            <div class="absolute inset-0 overflow-hidden glow outer-glow pointer-events-none z-50" :class="{'glow-active': glow === 2}">
                <div class="absolute rainbow opacity-80">
                    <div class="absolute h-16 top-0 w-full"></div>
                    <div class="absolute h-16 rotate-90 origin-[center_left] left-[calc(100%-2rem)] top-8 w-full"></div>
                    <div class="absolute h-16 -rotate-90 origin-[center_right] right-[calc(100%-2rem)] top-8 w-full"></div>
                    <div class="absolute h-16 bottom-0 w-full rotate-180"></div>
                </div>
            </div>
        </teleport>
        <div class="absolute inset-0 z-20">
            <div @mousedown.left="play('intro')" class="cursor-pointer absolute left-0 top-0 h-32 w-40"></div>
            <div @mousedown.left="play('rook')" class="cursor-pointer absolute left-40 top-8 h-16 w-44"></div>
            <div @mousedown.left="play('f')" class="cursor-pointer absolute right-16 top-8 h-16 w-8"></div>
            <div @mousedown.left="play('m')" class="cursor-pointer absolute right-0 top-12 h-12 w-16"></div>
        </div>
    </div>
</template>
<style scoped>
.glow {
    --blur: 20px;
    opacity: 0;
    transition: opacity 4s ease-in, filter 3s ease-in;
}

.glow:not(.rainbow) > div {
    filter: blur(5px);
}

.outer-glow {
    box-shadow: inset 2rem 2rem 50px #fffa, inset -2rem -2rem 50px #fffa;
}

.outer-glow > div {
    --blur: 30px;
    top: -4rem;
    left: -4rem;
    bottom: -4rem;
    right: -4rem;
    transition: filter 3s ease-in, top 3s ease-in, left 3s ease-in, bottom 3s ease-in, right 3s ease-in;
}

.rainbow > div {
    mask-size: contain;
    /**
      * Holy fucking shit this is a PhD in CSS
      *
      * https://stackoverflow.com/a/63791417
      * https://stackoverflow.com/a/51734530/8620333
      */
    --size: 300px;
    background-image: repeating-linear-gradient(45deg, #ff6262, #bb55ff, #2bfcc3, #98ff2f, #ffe660, #ff3b3b var(--size));
    background-size: calc(var(--size) / sin(45deg)) 100%;
    animation: 2s linear infinite rainbow-cycle;
}

.outer-glow.glow-active > div {
    transition: filter 1s ease-out, top 1s ease, left 1s ease, bottom 1s ease, right 1s ease;
    filter: blur(var(--blur));
    top: -2rem;
    left: -2rem;
    bottom: -2rem;
    right: -2rem;
}

.glow-active {
    transition: opacity 500ms ease, filter 1s ease-out;
    opacity: 1;
}

.glow-active:not(.outer-glow) {
    filter: blur(var(--blur));
}

@keyframes rainbow-cycle {
    0% {
        background-position: calc(var(--size) / sin(45deg)) 0;
    }
}
</style>
