<script setup>
import StartLink from "./StartLink.vue";
import StartSubLink from "./StartSubLink.vue";
import {Link} from '@inertiajs/vue3'
import {$$$, getUser, isAuthenticated, openW, logout, windowsLaunched} from "../util.js";
import {useTemplateRef} from "vue";
import WindowTab from "./WindowTab.vue";
import {onClickOutside, useDateFormat, useEventListener, useNow} from '@vueuse/core'

const menu = useTemplateRef('menu');
const start = useTemplateRef('start');

const { cancel, trigger } = onClickOutside(
    menu,
    (event) => {
        $$$.desktop.startMenuOpen = false
    },
    {
        controls: true,
        ignore: [start]
    },
)

useEventListener('mousedown', (e) => {
    if (start.value.contains(e.target)) {
        cancel()
    } else {
        trigger(e)
    }
})

const date = useDateFormat(useNow(), 'hh:mm A');
const time = useDateFormat(useNow(), 'YYYY/MM/DD');
</script>
<template>
    <div class="relative z-10 flex select-none m-2 justify-between items-center">
        <div ref="menu" v-show="$$$.desktop.startMenuOpen" class="start flex flex-col space-y-2 absolute bottom-12 left-0 p-2 w-64">
            <Link href="/profile" class="flex cursor-pointer w-full rounded items-center space-x-2 p-2">
                <div class="rounded-full p-2 bg-gradient-to-b from-gray-400 to-gray-600 h-fit text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">
                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                    </svg>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-bold text-lg">{{ isAuthenticated() ? getUser().name : 'Anonymous' }}</span>
                    <span class="text-small">{{ isAuthenticated() ? 'User' : 'Guest' }}</span>
                </div>
            </Link>
            <StartLink class="hidden md:flex" title="Programs">
                <StartSubLink @click="openW('Chat')">Chat</StartSubLink>
                <StartSubLink @click="openW('Fanclubs')">Clubs</StartSubLink>
                <StartSubLink @click="openW('About')">About</StartSubLink>
                <StartSubLink @click="openW('Settings')">Settings</StartSubLink>
                <StartSubLink @click="openW('Radio')">Radio</StartSubLink>
                <StartSubLink @click="openW('Feedback')">Feedback</StartSubLink>
                <StartSubLink @click="openW('Events')">
                    <div class="flex space-x-1 items-center">
                        <span>Events</span>
                        <span class="rounded text-white font-bold bg-red-500 text-xs px-1 py-0.5">NEW</span>
                    </div>
                </StartSubLink>
            </StartLink>
            <StartLink v-if="false" title="Documents">
                <p>woah</p>
                <p>woah</p>
            </StartLink>
            <StartLink title="Log Off" @click="logout"/>
        </div>
        <div class="taskbar flex items-center space-x-2 justify-between grow p-1.5 z-10">
            <div ref="start" class="flex items-center space-x-2 !ml-0">
                <div @click="$$$.desktop.startMenuOpen = !$$$.desktop.startMenuOpen" class="taskbar-tab flex space-x-1 items-center py-0.5 px-2 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4">
                        <path d="M14 1.75a.75.75 0 0 0-.89-.737l-7.502 1.43a.75.75 0 0 0-.61.736v2.5c0 .018 0 .036.002.054V9.73a1 1 0 0 1-.813.983l-.58.11a1.978 1.978 0 0 0 .741 3.886l.603-.115c.9-.171 1.55-.957 1.55-1.873v-1.543l-.001-.043V6.3l6-1.143v3.146a1 1 0 0 1-.813.982l-.584.111a1.978 1.978 0 0 0 .74 3.886l.326-.062A2.252 2.252 0 0 0 14 11.007V1.75Z" />
                    </svg>
                    <span>Start</span>
                </div>
                <div class="hidden md:flex items-center space-x-2">
                    <div class="py-2.5 border-x-[0.05rem] rounded border-gray-500"></div>
                    <WindowTab v-for="(tab, name) in windowsLaunched" :name="name"/>
                </div>
            </div>
            <div class="md:hidden"></div>
            <div class="px-2">
                <div class="flex flex-col text-sm space-x-2 items-end leading-none">
                    <span>{{ time }}</span>
                    <span>{{ date }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
