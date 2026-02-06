<script setup>
import track1 from './../../assets/audio/soulseek/STAY WITH ME.mp3';
import track2 from './../../assets/audio/soulseek/SOUL SEEK (feat. eik).mp3';
import track3 from './../../assets/audio/soulseek/corrupted_unknown.mp3';
import {useMediaControls} from "@vueuse/core";
import {useTemplateRef} from "vue";
import {hhmmss} from "../util.js";

const audio = useTemplateRef('audio');
const {playing, currentTime, duration, volume} = useMediaControls(audio, {
    src: track1,
});

const setTrack = (t) => {
    playing.value = false;
    audio.value.addEventListener('canplay', () => {
        playing.value = true;
    })
    audio.value.src = t;
}
</script>
<template>
    <div>
        <div class="altmenu">
            <span>Connections</span>
            <span>Options</span>
            <span>View</span>
            <span>Window</span>
            <span>Help!</span>
        </div>
        <div class="flex flex-wrap text-xs gap-1 p-1">
            <button>Search Files</button>
            <button>File Sharing</button>
            <button>Toggle Away</button>
            <button>Wishlist</button>
            <button>Things I Like</button>
            <button>Tile Windows</button>
        </div>
        <div class="table-container m-2">
            <table class="text-sm w-full h-full">
                <thead>
                <tr>
                    <th></th>
                    <th>Filename</th>
                    <th>Folder</th>
                    <th>User</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td @click="setTrack(track1)" class="!px-0 cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="text-green-500 size-5 ml-1">
                            <path d="M6.3 2.84A1.5 1.5 0 0 0 4 4.11v11.78a1.5 1.5 0 0 0 2.3 1.27l9.344-5.891a1.5 1.5 0 0 0 0-2.538L6.3 2.841Z" />
                        </svg>
                    </td>
                    <td>STAY_WITH_ME.mp3</td>
                    <td>SOULSEEK/</td>
                    <td>bunnyfan12</td>
                </tr>
                <tr>
                    <td @click="setTrack(track2)" class="!px-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="text-green-500 size-5 ml-1">
                            <path d="M6.3 2.84A1.5 1.5 0 0 0 4 4.11v11.78a1.5 1.5 0 0 0 2.3 1.27l9.344-5.891a1.5 1.5 0 0 0 0-2.538L6.3 2.841Z" />
                        </svg>
                    </td>
                    <td>SOUL SEEK (feat. eik).mp3</td>
                    <td>SOULSEEK (2026)/</td>
                    <td>bunnyfan12</td>
                </tr>
                <tr class="bg-red-400">
                    <td @click="setTrack(track3)" class="!px-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="text-green-500 size-5 ml-1">
                            <path d="M6.3 2.84A1.5 1.5 0 0 0 4 4.11v11.78a1.5 1.5 0 0 0 2.3 1.27l9.344-5.891a1.5 1.5 0 0 0 0-2.538L6.3 2.841Z" />
                        </svg>
                    </td>
                    <td>CORRUPTED_unknown.mp3</td>
                    <td>cds/swm2026/</td>
                    <td>anonymous</td>
                </tr>
                </tbody>
            </table>
        </div>
        <audio ref="audio"></audio>
        <div class="flex items-center space-x-2 px-4 text-xs pb-2">
            <svg @click="playing = !playing" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path d="M12.75 4a.75.75 0 0 0-.75.75v10.5c0 .414.336.75.75.75h.5a.75.75 0 0 0 .75-.75V4.75a.75.75 0 0 0-.75-.75h-.5ZM17.75 4a.75.75 0 0 0-.75.75v10.5c0 .414.336.75.75.75h.5a.75.75 0 0 0 .75-.75V4.75a.75.75 0 0 0-.75-.75h-.5ZM3.288 4.819A1.5 1.5 0 0 0 1 6.095v7.81a1.5 1.5 0 0 0 2.288 1.277l6.323-3.906a1.5 1.5 0 0 0 0-2.552L3.288 4.819Z" />
            </svg>
            <span>{{ hhmmss(currentTime) }}</span>
            <input v-model="currentTime" @input="e => currentTime = e.target.value" min="0" :max="duration" type="range" class="w-full mx-4"/>
            <span>{{ hhmmss(duration) }}</span>
        </div>
    </div>
</template>
