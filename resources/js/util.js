import {router, usePage} from "@inertiajs/vue3";
import route from 'ziggy-js'
import {computed, markRaw, reactive, ref} from "vue";
import default_bg from "../assets/wallpapers/hoosteen.jpg"
import About from "@/Windows/About.vue";
import Keygen from "@/Windows/Keygen.vue";
import Settings from "@/Windows/Settings.vue";
import Radio from "@/Windows/Radio.vue";
import Blog from "@/Windows/Blog.vue";
import Chatroom from "@/Windows/Chatroom.vue";
import Events from "@/Windows/Events.vue";
import Feedback from "@/Windows/Feedback.vue";
import {useStorage} from "@vueuse/core";
import Livestream from "@/Windows/Livestream.vue";
import {Window} from "./Window.js"
import Welcome from "@/Windows/Welcome.vue";
import Minesweeper from "@/Windows/Minesweeper.vue";
import Account from "@/Windows/Account.vue";

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

export const $$$ = reactive({
    theme: {
        wallpaper: useStorage('rk_wallpaper', default_bg),
        style: useStorage('rk_theme', 'hoo'),
    },
    windows: {
        'Radio': ref(new Window(markRaw(Radio), {
            title: 'Radio',
            visibility: WindowState.Open,
            resizable: false
        })),
        'Welcome': ref(new Window(markRaw(Welcome), {
            title: 'Welcome',
            visibility: WindowState.Open,
            resizable: false
        })),
        'Account': ref(new Window(markRaw(Account), {
            title: 'Account',
            visibility: WindowState.Closed,
            resizable: false
        })),
        'About': ref(new Window(markRaw(About), {
            title: 'About',
            style: 'items-center',
            resizable: false
        })),
        'Keygen': ref(new Window(markRaw(Keygen), {
            title: 'Keygen',
            visibility: WindowState.Closed,
            style: '!p-0',
            resizable: false
        })),
        'Settings': ref(new Window(markRaw(Settings), {
            title: 'Settings',
            resizable: false,
            decoration: false,
        })),
        'Fanclubs': ref(new Window(markRaw(Blog), {
            title: 'Fanclubs',
            decoration: false,
        })),
        'Chat': ref(new Window(markRaw(Chatroom), {
            title: 'Chat',
            decoration: false,
        })),
        'Events': ref(new Window(markRaw(Events), {
            title: 'Events',
            width: 400,
            resizable: false
        })),
        'Feedback': ref(new Window(markRaw(Feedback), {
            title: 'Feedback',
        })),
        // 'Submit': ref({
        //     resizable: false
        // }),
        'Livestream': ref(new Window(markRaw(Livestream), {
            title: 'Livestream',
        })),
        'Minesweeper': ref(new Window(markRaw(Minesweeper), {
            title: 'Minesweeper',
            resizable: false
        })),
    },
    desktop: {
        startMenuOpen: false,
        transforming: false,
        zHighest: 0,
    },
    snap: {
        x: 0,
        y: 0,
        height: 0,
        width: 0
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
    if ($$$.windows[name].z < $$$.desktop.zHighest) {
        $$$.windows[name].z = ++$$$.desktop.zHighest;
    } else {
        if ($$$.windows[name].visibility === WindowState.Minimized) {
            $$$.windows[name].visibility = WindowState.Open
        } else if ($$$.windows[name].visibility === WindowState.Open) {
            $$$.windows[name].visibility = WindowState.Minimized
        }
    }
}

export const openW = (name) => {
    $$$.windows[name].visibility = 2;
    $$$.windows[name].z = ++$$$.desktop.zHighest;
}
