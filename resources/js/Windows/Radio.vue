<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";
import {set} from "@vueuse/core";

const duration = ref(1);
const progress = ref(0);
const song = ref('Rook FM');

const muted = ref(false)
const playing = ref(false)

const audio = ref(new Audio())

const frames = ref(0);

onMounted(async () => {
    await fetchStream(true)

    audio.value.volume = 0.75;

    setInterval(() => {
        if (++progress.value >= duration.value) fetchStream()
    }, 1000);
    setInterval(() => frames.value++, 50);
})

onBeforeUnmount(() => {
    audio.value.src = '';
})

const fetchStream = async (setSource = false) => {
    let np = await fetch('https://radio.rook.fm/api/nowplaying/main').then(res => res.json())

    duration.value = np.now_playing.duration;
    progress.value = np.now_playing.elapsed;
    song.value = np.now_playing.song.text;

    if (setSource) {
        audio.value.src = np.station.listen_url;
        setTimeout(() => audio.value.play(), 500)
    }
}

const pretty = (t) => {
    let sec_num = parseInt(t, 10);
    let hours   = Math.floor(sec_num / 3600);
    let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    let seconds = sec_num - (hours * 3600) - (minutes * 60);

    let output = '';
    //if (hours > 0) {
        output += hours+':';
    //}

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}

    output += minutes+':'+seconds;
    return output;
}

const volume = ref(0.75);

const setVol = () => {
    audio.value.volume = volume.value
}
</script>
<template>
    <div class="flex justify-between">
        <h1 class="text-2xl font-bold">Montaime Radio</h1>
        <div class="flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
            </svg>
            <input class="w-24" @input="setVol" v-model.number="volume" min="0" max="1" step="0.01" type="range"/>
        </div>
    </div>
    <div class="flex w-full bg-black rounded">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="text-white p-1 w-16 h-16 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 7.5 16.5-4.125M12 6.75c-2.708 0-5.363.224-7.948.655C2.999 7.58 2.25 8.507 2.25 9.574v9.176A2.25 2.25 0 0 0 4.5 21h15a2.25 2.25 0 0 0 2.25-2.25V9.574c0-1.067-.75-1.994-1.802-2.169A48.329 48.329 0 0 0 12 6.75Zm-1.683 6.443-.005.005-.006-.005.006-.005.005.005Zm-.005 2.127-.005-.006.005-.005.005.005-.005.005Zm-2.116-.006-.005.006-.006-.006.005-.005.006.005Zm-.005-2.116-.006-.005.006-.005.005.005-.005.005ZM9.255 10.5v.008h-.008V10.5h.008Zm3.249 1.88-.007.004-.003-.007.006-.003.004.006Zm-1.38 5.126-.003-.006.006-.004.004.007-.006.003Zm.007-6.501-.003.006-.007-.003.004-.007.006.004Zm1.37 5.129-.007-.004.004-.006.006.003-.004.007Zm.504-1.877h-.008v-.007h.008v.007ZM9.255 18v.008h-.008V18h.008Zm-3.246-1.87-.007.004L6 16.127l.006-.003.004.006Zm1.366-5.119-.004-.006.006-.004.004.007-.006.003ZM7.38 17.5l-.003.006-.007-.003.004-.007.006.004Zm-1.376-5.116L6 12.38l.003-.007.007.004-.004.007Zm-.5 1.873h-.008v-.007h.008v.007ZM17.25 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 4.5a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
        </svg>
        <div class="flex space-x-1 items-end justify-between px-2 py-2 w-full">
            <span v-for="n in 40" class="bg-white pl-1" :style="`height: ${2 + Math.trunc((Math.sin(n * 0.2 + frames/5) + 1) * (Math.sin(n * 3 + frames) + 1) * 4 + (Math.sin(n - frames/20) + 1) * 9)}px`"></span>
        </div>
    </div>
    <div class="flex items-center text-sm space-x-1 px-1">
        <svg @click="audio.paused ? fetchStream(true) : audio.src = ''" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
        </svg>
        <span class="font-mono">{{ pretty(progress) }}</span>
        <div class="flex items-center h-fit p-0.5 rounded-full bg-gray-200 w-full">
            <div class="h-fit rounded-full bg-blue-500 p-1" :style="`width: ${ progress / duration * 100 }%`"></div>
        </div>
        <span class="font-mono">{{ pretty(duration) }}</span>
    </div>
    <div class="marqueebox relative flex overflow-x-hidden w-full rounded bg-black font-mono text-white">
        <div class="marquee whitespace-nowrap" aria-hidden="true">
            <span class="mx-4">{{ song }}</span>
            <span class="mx-4">{{ song }}</span>
            <span class="mx-4">{{ song }}</span>
            <span class="mx-4">{{ song }}</span>
        </div>
        <div class="absolute top-0 marquee2 whitespace-nowrap" aria-hidden="true">
            <span class="mx-4">{{ song }}</span>
            <span class="mx-4">{{ song }}</span>
            <span class="mx-4">{{ song }}</span>
            <span class="mx-4">{{ song }}</span>
        </div>
    </div>
</template>
<style scoped>
.marquee {
    animation: marquee 15s linear infinite;
}

.marquee2 {
    animation: marquee2 15s linear infinite;
}

@keyframes marquee {
    0% { transform: translateX(0%) }
    100% { transform: translateX(-100%) }
}

@keyframes marquee2 {
    0% { transform: translateX(100%) }
    100% { transform: translateX(0%) }
}
</style>
