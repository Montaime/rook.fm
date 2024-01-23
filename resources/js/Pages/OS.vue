<script setup>
import Window from "@/Components/OS/Window.vue";
import poolbg from "@/../assets/poolbg.jpg"
import bliss from "@/../assets/reddit.png"
import rook from "@/../assets/Montaime_Rook2.webp"
import {ref} from "vue";
import NewPost from "@/Pages/Demo/NewPost.vue";

const windows = ref({
    0: Window,
    1: Window,
    2: Window,
});

const activeTheme = ref(poolbg)
const uiTheme = ref('brutal')

const blog = [
    {
        title: 'New Releases!',
        content: 'Hey all! I am very excited to share that I am a part of the new Montaime Vol. 3! I have been working very hard to make this possible because as you know many years ago I made a bargain with a witch who took my vas deferens in exchange for my fantastic musical ability. Well about 2 weeks ago...'
    }
]

const chat = [
    {
        user: 'user1337',
        content: 'yo yo havent logged on in a minute'
    },
    {
        user: 'scruncklo',
        content: 'Hey 1337! been awhile!'
    },
    {
        user: 'user1337',
        content: 'yeah ikr'
    },
    {
        user: 'user1337',
        content: 'have you heard the new cyan blue?'
    },
    {
        user: 'scrunklo',
        content: 'scrunklo has left the chatroom',
        type: 'LEAVE'
    },
    {
        user: 'user1337',
        content: 'ok hater'
    },
];

const startOpen = ref(false);
</script>
<template>
    <div :class="uiTheme" class="flex flex-col h-screen w-screen overflow-hidden bg-cover" :style="`background-image: url('${activeTheme}')`">
        <div class="grow overflow-hidden">
            <div class="h-full text-white w-full grid grid-flow-row grid-rows-12 gap-2 p-0.5">
                <div class="flex flex-col items-center space-y-1 w-fit h-fit p-2 border border-black bg-cyan-400/50 border-dashed">
                    <svg class="fill-white stroke-black" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                    <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">File Name</span>
                </div>
                <div class="flex flex-col items-center space-y-1 w-fit h-fit p-2">
                    <svg class="fill-white stroke-black" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                    <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">File Name</span>
                </div>
                <div class="flex flex-col items-center space-y-1 w-fit h-fit p-2">
                    <svg class="fill-white stroke-black" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                    <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">File Name</span>
                </div>
                <div class="flex flex-col items-center space-y-1 w-fit h-fit p-2">
                    <svg class="fill-white stroke-black" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
                    <path d="M320,0v128h128L320,0z M298.7,0H64v512h384V149.3H298.7V0z"/>
                    </svg>
                    <span class="text-xs" style="text-shadow: #232323 1px 1px 1px">File Name</span>
                </div>
            </div>
        </div>
        <div class="relative flex select-none justify-between items-center space-x-2 bg-gradient-to-b from-gray-300 to-gray-400 p-1">
            <div v-show="startOpen" class="flex flex-col space-y-2 absolute bg-gradient-to-br from-gray-50 to-gray-300 rounded shadow-xl bottom-12 p-2 w-64">
                <div class="flex cursor-pointer hover:bg-gradient-to-b from-gray-300 to-gray-200 border-y border-transparent hover:border-t-gray-400 hover:border-b-white w-full rounded items-center space-x-2 p-2">
                    <div class="rounded-full p-5 bg-blue-400 h-fit"></div>
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-lg">SeeBeyond</span>
                        <span class="text-small">Administrator</span>
                    </div>
                </div>
                <div v-for="link in ['Programs', 'Documents', 'Settings', 'Help', 'Log Off']" class="flex cursor-pointer bg-gradient-to-b shadow from-white to-gray-200 hover:!bg-white items-center justify-between rounded px-2 py-1 w-full">
                    <span>{{ link }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center space-x-2 !ml-0">
                <div @click="startOpen = !startOpen" class="flex space-x-1 items-center py-0.5 px-2 shadow rounded-md border-y border-t-white border-b-gray-300 bg-gradient-to-b from-gray-50 to-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                        <path d="M14 1.75a.75.75 0 0 0-.89-.737l-7.502 1.43a.75.75 0 0 0-.61.736v2.5c0 .018 0 .036.002.054V9.73a1 1 0 0 1-.813.983l-.58.11a1.978 1.978 0 0 0 .741 3.886l.603-.115c.9-.171 1.55-.957 1.55-1.873v-1.543l-.001-.043V6.3l6-1.143v3.146a1 1 0 0 1-.813.982l-.584.111a1.978 1.978 0 0 0 .74 3.886l.326-.062A2.252 2.252 0 0 0 14 11.007V1.75Z" />
                    </svg>
                    <span>Start</span>
                </div>
                <div class="py-2.5 border-x-[0.05rem] rounded border-gray-500"></div>
                <div v-for="tab in ['Artist Blog', 'Chatroom', 'Radio', 'Debug Menu', 'About', 'Notepad', 'Command Prompt']" class="flex space-x-1 items-center py-0.5 px-2 shadow rounded-md border-y border-t-white border-b-gray-300 bg-gradient-to-b from-gray-50 to-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M2 12V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2Zm1.5-5.5V12a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5V6.5A.5.5 0 0 0 12 6H4a.5.5 0 0 0-.5.5Zm.75-1.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM7 4a.75.75 0 1 1-1.5 0A.75.75 0 0 1 7 4Zm1.25.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ tab }}</span>
                </div>
            </div>
            <div class="px-2">
                <div class="flex text-xs leading-none flex-col">
                    <span>13:93 PM</span>
                    <span>1/1/2000</span>
                </div>
            </div>
        </div>
    </div>
    <div :class="uiTheme">
        <Window :x="10" :y="30" container-class="flex flex-col divide-y" title="Change Styles">
            <div class="flex space-x-2 py-2">
                <div class="aspect-video p-4 rounded bg-red-500 h-32"></div>
                <div class="flex flex-col">
                    <h1 class="font-bold text-2xl">Red Theme</h1>
                    <p>Our reddest desktop theme</p>
                </div>
            </div>
            <div class="flex space-x-2 py-2">
                <div class="aspect-video p-4 rounded bg-cyan-500 h-32"></div>
                <div class="flex flex-col">
                    <h1 class="font-bold text-2xl">Cyan Theme</h1>
                    <p>Have you heard the new Cyan Blue?</p>
                </div>
            </div>
            <div @click="activeTheme = poolbg" class="cursor-pointer flex space-x-2 py-2">
                <div class="aspect-video p-4 rounded bg-red-500 h-32" :style="`background: url('${poolbg}');`"></div>
                <div class="flex flex-col">
                    <h1 class="font-bold text-2xl">Pool Theme</h1>
                    <p>Poooooool Siiiiide</p>
                </div>
            </div>
            <div @click="activeTheme = bliss" class="cursor-pointer flex space-x-2 py-2">
                <div class="aspect-video p-4 rounded bg-red-500 h-32" :style="`background: url('${bliss}');`"></div>
                <div class="flex flex-col">
                    <h1 class="font-bold text-2xl">Aesthetic Theme</h1>
                    <p>Ignorance is Bliss</p>
                </div>
            </div>
            {{uiTheme}}
            <button @click="uiTheme = 'brutal'">Brutal Theme</button>
            <button @click="uiTheme = 'glass'">Glass Theme</button>
        </Window>
        <Window :x="900" :y="330" container-class="flex flex-col space-y-2" title="Radio">
            <h1 class="text-2xl font-bold">Montaime Radio</h1>
            <div class="flex w-full bg-black rounded">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="text-white p-1 w-16 h-16 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 7.5 16.5-4.125M12 6.75c-2.708 0-5.363.224-7.948.655C2.999 7.58 2.25 8.507 2.25 9.574v9.176A2.25 2.25 0 0 0 4.5 21h15a2.25 2.25 0 0 0 2.25-2.25V9.574c0-1.067-.75-1.994-1.802-2.169A48.329 48.329 0 0 0 12 6.75Zm-1.683 6.443-.005.005-.006-.005.006-.005.005.005Zm-.005 2.127-.005-.006.005-.005.005.005-.005.005Zm-2.116-.006-.005.006-.006-.006.005-.005.006.005Zm-.005-2.116-.006-.005.006-.005.005.005-.005.005ZM9.255 10.5v.008h-.008V10.5h.008Zm3.249 1.88-.007.004-.003-.007.006-.003.004.006Zm-1.38 5.126-.003-.006.006-.004.004.007-.006.003Zm.007-6.501-.003.006-.007-.003.004-.007.006.004Zm1.37 5.129-.007-.004.004-.006.006.003-.004.007Zm.504-1.877h-.008v-.007h.008v.007ZM9.255 18v.008h-.008V18h.008Zm-3.246-1.87-.007.004L6 16.127l.006-.003.004.006Zm1.366-5.119-.004-.006.006-.004.004.007-.006.003ZM7.38 17.5l-.003.006-.007-.003.004-.007.006.004Zm-1.376-5.116L6 12.38l.003-.007.007.004-.004.007Zm-.5 1.873h-.008v-.007h.008v.007ZM17.25 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Zm0 4.5a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                </svg>
                <div class="flex space-x-1 items-end justify-between px-2 py-2 w-full">
                    <span v-for="n in 40" class="bg-white pl-1" :style="`height: ${2 + Math.trunc((Math.sin(n * 0.2) + 1) * (Math.sin(n * 3) + 1) * 4 + (Math.sin(n) + 1) * 9)}px`"></span>
                </div>
            </div>
            <div class="flex items-center text-sm space-x-1 px-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75 19.5 12m0 0 2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6 4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                </svg>
                <span>0:50</span>
                <div class="flex items-center h-fit p-0.5 rounded-full bg-gray-200 w-full">
                    <div class="h-fit rounded-full bg-blue-500 p-1" style="width: 75%"></div>
                </div>
                <span>1:40</span>
            </div>
            <marquee class="w-full rounded bg-black font-mono text-white">The Phantoms Revenge - Charlie [All You Can Eat]</marquee>
        </Window>
        <Window :x="240" :y="230" container-class="flex flex-col  space-y-2" title="Chatroom">
            <div class="grow justify-self-end flex flex-col">
                <div class="flex items-center justify-center !border-none">
                    <span class="border-t w-full"></span>
                    <span class="w-full text-center">January 1st, 2000</span>
                    <span class="border-t w-full"></span>
                </div>
                <div v-for="post in chat" class="">
                    <span class="text-xs">{{ post.user }}</span>
                    <p :class="{'italic text-gray-500': post.type === 'LEAVE'}" class="px-2 py-1 rounded-md w-fit bg-blue-200">{{ post.content }}</p>
                </div>

            </div>
            <input type="text" placeholder="Say something nice..."/>
        </Window>
        <Window :x="450" :y="530" container-class="flex flex-col space-y-2" title="Artist Blog">
            <div class="flex flex-col divide-y">
                <div v-for="post in blog" class="leading-0">
                    <h1 class="font-bold text-lg">{{ post.title }}</h1>
                    <p>{{ post.content }} <span class="underline italic">Read More</span></p>
                </div>
            </div>
        </Window>
        <Window :x="750" :y="500" container-class="flex flex-col !p-0" title="Notepad">
            <div class="text-sm bg-white/75">
                <button class="px-2 py-0.5 hover:bg-gray-200/75">File</button>
                <button class="px-2 py-0.5 hover:bg-gray-200/75">Edit</button>
                <button class="px-2 py-0.5 hover:bg-gray-200/75">View</button>
                <button class="px-2 py-0.5 hover:bg-gray-200/75">Help</button>
            </div>
            <textarea class="h-full p-1 resize-none bg-white font-mono">hello youtube...</textarea>
        </Window>
        <Window :x="2050" :y="830" container-class="!p-0 flex flex-col space-y-2" title="Command Prompt">
            <textarea class="h-full p-1 resize-none bg-black text-white font-mono text-sm">guest@rook.fm $</textarea>
        </Window>
        <Window :x="990" :y="480" container-class="flex flex-col items-center space-y-2" title="About">
            <img :src="rook" alt="" class="h-72"/>
            <h1 class="text-4xl font-bold">rook.fm</h1>
            <h2>Web Operating System</h2>
            <span class="bg-black rounded px-1 text-white text-xs font-mono">0.1.0+ALPHA.1</span>
        </Window>
        <Window :x="990" :y="480" container-class="flex flex-col items-center space-y-2" title="New Club Post">
            <NewPost/>
        </Window>
    </div>
</template>
