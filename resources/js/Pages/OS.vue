<script setup>
import Window from "@/Components/OS/Window.vue";
import poolbg from "@/../assets/wesley-tingey-9-OCsKoyQlk-unsplash.jpg"
import vol3 from "@/../assets/vol3.png"
import marble from "@/../assets/mere.png"
import {computed, onBeforeMount, ref} from "vue";
import About from "../Windows/About.vue";
import Radio from "../Windows/Radio.vue";
import Chatroom from "../Windows/Chatroom.vue";
import {getUser, isAuthenticated, logout} from "../util.js";
import StartLink from "../Components/StartLink.vue";
import {Link} from '@inertiajs/vue3'
import Settings from "../Windows/Settings.vue";
import Blog from "../Windows/Blog.vue";

const WState = {
    Closed: 0,
    Minimized: 1,
    Open: 2,
};

const windows = ref({
    'About': ref({
        visibility: WState.Closed,
        style: 'items-center',
        x: 0,
        y: 0,
        z: 0
    }),
    'Settings': ref({
        visibility: WState.Closed,
        style: 'divide-y',
        x: 0,
        y: 0,
        z: 0
    }),
    'Radio': ref({
        visibility: WState.Open,
        x: 0,
        y: 0,
        z: 0
    }),
    'Blog': ref({
        visibility: WState.Closed,
        x: 0,
        y: 0,
        z: 0
    }),
    'Chat': ref({
        visibility: WState.Closed,
        x: 0,
        y: 0,
        z: 0
    }),
});

const cMap = {
    'About': About,
    'Settings': Settings,
    'Radio': Radio,
    'Blog': Blog,
    'Chat': Chatroom,
}

const zHighest = ref(0);

const activeTheme = ref(poolbg)
const uiTheme = ref('glass')

const startOpen = ref(false);

const clicked = ref(false);

const d_time = ref(0);
const d_timeS = ref(0);

setInterval(() => {
    d_time.value++;
}, 50)
setInterval(() => {
    d_timeS.value++;
    datetime.value = new Date()
}, 1000)

const tabMenu = (e) => {
    e.preventDefault()
}

const datetime = ref(new Date());

const windowsLaunched = computed(() => {
    let list = {};

    for (let w in windows.value) {
        if (windows.value[w].visibility > WState.Closed) {
            list[w] = windows.value[w]
        }
    }

    return list;
})

const tabClick = (name) => {
    if (windows.value[name].visibility === WState.Minimized) {
        windows.value[name].visibility = WState.Open
    } else if (windows.value[name].visibility === WState.Open) {
        windows.value[name].visibility = WState.Minimized
    }
}
</script>
<template>
    <div class="root flex flex-col h-screen w-screen overflow-hidden bg-cover" :style="`background-image: url('${activeTheme}')`">
        <div class="invisible grow overflow-hidden">
            <div class="h-full text-white w-full grid grid-flow-row grid-rows-12 gap-4 p-0.5">
                <div @click="d_timeS = 0" class="flex flex-col items-center space-y-1 w-8 h-fit p-2 px-10 border border-black bg-cyan-400/50 border-dashed">
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
        <div class="relative z-10 flex select-none m-2 rounded-md justify-between items-center space-x-2 bg-gradient-to-b from-gray-300 to-gray-400 p-1.5">
            <div v-show="startOpen" class="flex flex-col space-y-2 absolute bg-gradient-to-br from-gray-50 to-gray-300 rounded shadow-xl bottom-12 left-0 p-2 w-64">
                <Link href="/profile" class="flex cursor-pointer hover:bg-gradient-to-b from-gray-300 to-gray-200 border-y border-transparent hover:border-t-gray-400 hover:border-b-white w-full rounded items-center space-x-2 p-2">
                    <div class="rounded-full p-5 bg-gradient-to-b from-gray-400 to-gray-600 h-fit"></div>
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-lg">{{ isAuthenticated() ? getUser().name : 'Anonymous' }}</span>
                        <span class="text-small">{{ isAuthenticated() ? 'User' : 'Guest' }}</span>
                    </div>
                </Link>
                <StartLink title="Programs" >
                    <button @click="windows['Chat'].visibility = 2" class="px-2 py-1 rounded-t-md hover:bg-gray-300 text-left">Chat</button>
                    <button @click="windows['Blog'].visibility = 2" class="px-2 py-1 hover:bg-gray-300 text-left">Blog</button>
                    <button @click="windows['About'].visibility = 2" class="px-2 py-1 hover:bg-gray-300 text-left">About</button>
                    <button @click="windows['Settings'].visibility = 2" class="px-2 py-1 hover:bg-gray-300 text-left">Settings</button>
                    <button @click="windows['Radio'].visibility = 2" class="px-2 py-1 rounded-b-md hover:bg-gray-300 text-left">Radio</button>
                </StartLink>
                <StartLink v-if="false" title="Documents">
                    <p>woah</p>
                    <p>woah</p>
                </StartLink>
                <StartLink title="Log Off" @click="logout"/>
            </div>
            <div class="flex items-center space-x-2 !ml-0">
                <div @click="startOpen = !startOpen" class="flex space-x-1 items-center py-0.5 px-2 shadow rounded-md border-y border-t-white border-b-gray-300 bg-gradient-to-b from-gray-50 to-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                        <path d="M14 1.75a.75.75 0 0 0-.89-.737l-7.502 1.43a.75.75 0 0 0-.61.736v2.5c0 .018 0 .036.002.054V9.73a1 1 0 0 1-.813.983l-.58.11a1.978 1.978 0 0 0 .741 3.886l.603-.115c.9-.171 1.55-.957 1.55-1.873v-1.543l-.001-.043V6.3l6-1.143v3.146a1 1 0 0 1-.813.982l-.584.111a1.978 1.978 0 0 0 .74 3.886l.326-.062A2.252 2.252 0 0 0 14 11.007V1.75Z" />
                    </svg>
                    <span>Start</span>
                </div>
                <div class="py-2.5 border-x-[0.05rem] rounded border-gray-500"></div>
                <div @contextmenu.stop="tabMenu" v-for="(tab, name) in windowsLaunched" @click="tabClick(name)" class="flex space-x-1 items-center py-0.5 px-2 shadow rounded-md border-y border-t-white border-b-gray-300 bg-gradient-to-b from-gray-50 to-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M2 12V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2Zm1.5-5.5V12a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V6.5A.5.5 0 0 0 12 6H4a.5.5 0 0 0-.5.5Zm.75-1.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM7 4a.75.75 0 1 1-1.5 0A.75.75 0 0 1 7 4Zm1.25.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ name }}</span>
                </div>
            </div>
            <div class="px-2">
                <div class="flex space-x-2 items-end leading-none">
                    <span>{{ datetime.toLocaleTimeString() }}</span>
                    <span>{{ datetime.toDateString() }}</span>
                </div>
            </div>
        </div>
    </div>
    <div v-if="!clicked" @click="clicked = true" class="flex flex-col cursor-pointer items-center justify-center absolute inset-0 backdrop-blur-md z-10">
        <div class="cursor-pointer rounded-full p-8 bg-white/50 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
            </svg>
        </div>
        <p class="text-4xl cursor-pointer font-bold mt-4">Click Anywhere To Unlock</p>
    </div>
    <div v-if="clicked" @mousedown="zHighest++" :class="uiTheme" class="relative z-0">
        <Window v-for="(w, key) in windows" v-model="windows[key]" :z-highest="zHighest" :x="Math.floor(Math.random() * 450) + 50" :y="Math.floor(Math.random() * 450) + 50" :container-class="w.style" :title="key">
            <component :is="cMap[key]"/>
        </Window>
        <Window v-if="false" :z-highest="zHighest" :visible="false" :x="200" :y="200" container-class="flex flex-col !p-0" title="Notepad">
            <div class="text-sm bg-white/75">
                <button class="px-2 py-0.5 hover:bg-gray-200/75">File</button>
                <button class="px-2 py-0.5 hover:bg-gray-200/75">Edit</button>
                <button class="px-2 py-0.5 hover:bg-gray-200/75">View</button>
                <button class="px-2 py-0.5 hover:bg-gray-200/75">Help</button>
            </div>
            <textarea class="h-full p-1 resize-none bg-white font-mono"></textarea>
        </Window>
        <Window v-if="false" :z-highest="zHighest" :visible="false" :x="230" :y="530" container-class="!p-0 flex flex-col space-y-2" title="Command Prompt">
            <textarea class="h-full p-1 resize-none bg-black text-white font-mono text-sm">guest@rook.fm $</textarea>
        </Window>
    </div>
</template>
