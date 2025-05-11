<script setup>
import {onBeforeUnmount, onMounted, ref} from "vue";

const duration = ref(1);
const progress = ref(0);
const song = ref('Rook FM');
const art = ref(null);

const muted = ref(false)
const playing = ref(false)

const audio = ref(new Audio())

const frames = ref(0);

onMounted(async () => {
    await fetchStream(!true)

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
    art.value = np.now_playing.song.art;

    if (setSource) {
        audio.value.src = np.station.listen_url;
        setTimeout(() => audio.value.play(), 500)
        playing.value = true;
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

const pauseClick = () => {
    if (audio.value.paused) {
        fetchStream(true)
    } else {
        audio.value.src = '';
        playing.value = false;
    }
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
    <div class="flex items-center space-x-2">
        <div class="shrink-0">
            <img :src="art" alt="" class="h-20 w-20 rounded"/>
        </div>
        <div class="flex w-full bg-black rounded h-20">
            <div class="flex space-x-1 items-end justify-between px-2 py-2 w-full">
                <span v-for="n in 30" class="bg-white pl-1 grow" :style="`height: ${playing ? 2 + Math.trunc((Math.sin(n * 0.2 + frames/5) + 1) * (Math.sin(n * 3 + frames) + 1) * 4 + (Math.sin(n - frames/20) + 1) * 9) : 4}px`"></span>
            </div>
        </div>
    </div>
    <div class="flex items-center text-sm space-x-1 px-1">
        <svg v-if="playing" @click="pauseClick" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8">
            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm6-2.438c0-.724.588-1.312 1.313-1.312h4.874c.725 0 1.313.588 1.313 1.313v4.874c0 .725-.588 1.313-1.313 1.313H9.564a1.312 1.312 0 0 1-1.313-1.313V9.564Z" clip-rule="evenodd" />
        </svg>
        <svg v-else @click="pauseClick" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm14.024-.983a1.125 1.125 0 0 1 0 1.966l-5.603 3.113A1.125 1.125 0 0 1 9 15.113V8.887c0-.857.921-1.4 1.671-.983l5.603 3.113Z" clip-rule="evenodd" />
        </svg>
        <span class="font-mono">{{ pretty(progress) }}</span>
        <div class="flex items-center h-fit p-0.5 rounded-full bg-gray-200 shadow-md w-full">
            <div class="h-fit rounded-full bg-blue-500 p-1" :style="`width: ${ progress / duration * 100 }%`"></div>
        </div>
        <span class="font-mono">{{ pretty(duration) }}</span>
    </div>
    <span class="marqueebox flex w-full rounded bg-black font-mono text-white px-2">{{ playing ? song : 'Click to Play' }}</span>
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
