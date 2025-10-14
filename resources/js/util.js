import {router, usePage} from "@inertiajs/vue3";
import route from 'ziggy-js'
import {computed, reactive, ref} from "vue";
import poolbg from "../assets/wallpapers/pool.jpg"
import About from "@/Windows/About.vue";
import Settings from "@/Windows/Settings.vue";
import Radio from "@/Windows/Radio.vue";
import Blog from "@/Windows/Blog.vue";
import Chatroom from "@/Windows/Chatroom.vue";
import Events from "@/Windows/Events.vue";
import Submit from "@/Windows/Submit.vue";
import Feedback from "@/Windows/Feedback.vue";
import {useStorage} from "@vueuse/core";
import Livestream from "@/Windows/Livestream.vue";

export const logout = () => {
    router.post(route('logout'));
}

export const isAdmin = () => {
    return isAuthenticated() && [1, 3].includes(getUser().id)
}

export const isAuthenticated = () => {
    return usePage().props.auth;
}

export const getUser = () => {
    return usePage().props.user;
}

export const WindowState = {
    Closed: 0,
    Minimized: 1,
    Open: 2,
}

export const cMap = {
    'About': About,
    'Settings': Settings,
    'Radio': Radio,
    'Fanclubs': Blog,
    'Chat': Chatroom,
    'Events': Events,
    'Submit': Submit,
    'Feedback': Feedback,
    'Livestream': Livestream,
}

export const $$$ = reactive({
    theme: {
        wallpaper: useStorage('rk_wallpaper', poolbg),
        style: useStorage('rk_theme', 'glass'),
    },
    windows: {
        'About': ref({
            visibility: WindowState.Closed,
            style: 'items-center',
            x: 0,
            y: 0,
            z: 0,
            resizable: false
        }),
        'Settings': ref({
            visibility: WindowState.Closed,
            style: 'divide-y',
            x: 0,
            y: 0,
            z: 0,
            resizable: false
        }),
        'Radio': ref({
            visibility: WindowState.Open,
            x: 0,
            y: 0,
            z: 0,
            resizable: false
        }),
        'Fanclubs': ref({
            visibility: WindowState.Closed,
            x: 0,
            y: 0,
            z: 0,
            resizable: false
        }),
        'Chat': ref({
            visibility: WindowState.Closed,
            x: 0,
            y: 0,
            z: 0,
            resizable: false
        }),
        'Events': ref({
            visibility: WindowState.Closed,
            x: 0,
            y: 0,
            z: 0,
            resizable: false
        }),
        // 'Submit': ref({
        //     visibility: WindowState.Closed,
        //     x: 0,
        //     y: 0,
        //     z: 0,
        //     resizable: false
        // }),
        'Feedback': ref({
            visibility: WindowState.Closed,
            x: 0,
            y: 0,
            z: 0,
            resizable: false
        }),
        // 'Livestream': ref({
        //     visibility: WindowState.Open,
        //     x: 0,
        //     y: 0,
        //     z: 0,
        //     resizable: true
        // }),
    },
    desktop: {
        startMenuOpen: false,
        zHighest: 0
    }
})

export const windowsLaunched = computed(() => {
    let list = {};

    for (let w in $$$.windows) {
        if ($$$.windows[w].visibility > WindowState.Closed) {
            list[w] = $$$.windows[w]
        }
    }

    return list;
})

export const tabClick = (name) => {
    if ($$$.windows[name].visibility === WindowState.Minimized) {
        $$$.windows[name].visibility = WindowState.Open
    } else if ($$$.windows[name].visibility === WindowState.Open) {
        $$$.windows[name].visibility = WindowState.Minimized
    }
}

export const openW = (name) => {
    $$$.windows[name].visibility = 2;
    $$$.windows[name].z = ++$$$.desktop.zHighest;
}
