<script setup>
import {onBeforeMount, ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";
import {useDateFormat} from "@vueuse/core";
import {Link} from "@inertiajs/vue3";
import route from "ziggy-js";

const chat = ref([]);

onBeforeMount(async () => {
    fetch('/chat').then(res => res.json()).then(data => {
        let msgs = [];
        for (let m in data) {
            msgs.push({
                user: data[m].author.name,
                content: data[m].message,
                created_at: data[m].created_at,
                type: isAuthenticated() ? (getUser().id === data[m].user_id ? 'SEND' : '') : ''
            });
        }
        msgs = msgs.reverse();
        chat.value.push(...msgs);

        //Echo.leaveAllChannels();
        Echo.channel('chatroom')
            .listen('ChatMessageCreated', (e) => {
                //console.log(e);
                chat.value.push({
                    user: e.user.name,
                    content: e.message.content,
                    created_at: e.message.created_at,
                    type: isAuthenticated() ? (getUser().id === e.user.id ? 'SEND' : '') : ''
                });

                // TODO: toggle autoscroll
                base.value.parentElement.scrollTop = base.value.parentElement.scrollHeight;
            });
    })
})

const sending = ref(false);

const base = ref(null)

const sendMessage = () => {
    if (msg.value.length === 0 || sending.value) return;

    sending.value = true;

    axios.post('/chat/send', {
        message: msg.value
    }).then(() => {
        msg.value = '';
        sending.value = false;
    })
}

const keypress = (e) => {
    if (e.keyCode === 13) sendMessage()
}

const msg = ref('');

const openChannels = ref(!true);
const openMembers = ref(!true);

// TOOD: show faded message while sending and no responde/confirmation/echo fom server yet!!
const channels = {
    lounge: 'a cool place to hang out and stuff',
    radio: 'a cool place to hang out and stuff',
    help: 'a cool place to hang out and stuff',
    gaming: 'a cool place to hang out and stuff',
}
</script>
<template>
    <div class="flex divide-x h-full">
        <div class="flex flex-col space-y-0.5 pr-1 w-48 shrink-0 select-none" v-if="openChannels">
            <span class="text-sm font-bold px-2 pt-1">CHANNELS</span>
            <div v-for="(description, key) in channels" class="flex items-center space-x-1">
                <div class="rounded-r-full h-2 w-1 bg-neutral-800"></div>
                <div class="flex grow items-center space-x-1 px-2 py-0.5 rounded-md cursor-pointer hover:bg-neutral-200/50" :class="{'bg-neutral-300/50': key === 'lounge'}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path fill-rule="evenodd" d="M9.493 2.852a.75.75 0 0 0-1.486-.204L7.545 6H4.198a.75.75 0 0 0 0 1.5h3.14l-.69 5H3.302a.75.75 0 0 0 0 1.5h3.14l-.435 3.148a.75.75 0 0 0 1.486.204L7.955 14h2.986l-.434 3.148a.75.75 0 0 0 1.486.204L12.456 14h3.346a.75.75 0 0 0 0-1.5h-3.14l.69-5h3.346a.75.75 0 0 0 0-1.5h-3.14l.435-3.148a.75.75 0 0 0-1.486-.204L12.045 6H9.059l.434-3.148ZM8.852 7.5l-.69 5h2.986l.69-5H8.852Z" clip-rule="evenodd" />
                    </svg>
                    <h2 class="font-bold pb-1 grow">{{ key }}</h2>
                    <span class="text-sm">2</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                        <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                    </svg>
                </div>
            </div>
        </div>
          <div class="relative grow">
            <div class="flex flex-col absolute inset-0 overflow-x-hidden">
                <div class="flex items-center border-b p-2 text-nowrap space-x-2 chatroom-header">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0">
                        <path fill-rule="evenodd" d="M9.493 2.852a.75.75 0 0 0-1.486-.204L7.545 6H4.198a.75.75 0 0 0 0 1.5h3.14l-.69 5H3.302a.75.75 0 0 0 0 1.5h3.14l-.435 3.148a.75.75 0 0 0 1.486.204L7.955 14h2.986l-.434 3.148a.75.75 0 0 0 1.486.204L12.456 14h3.346a.75.75 0 0 0 0-1.5h-3.14l.69-5h3.346a.75.75 0 0 0 0-1.5h-3.14l.435-3.148a.75.75 0 0 0-1.486-.204L12.045 6H9.059l.434-3.148ZM8.852 7.5l-.69 5h2.986l.69-5H8.852Z" clip-rule="evenodd" />
                    </svg>
                    <h2 class="font-bold">Lounge</h2>
                    <span>&middot;</span>
                    <p class="text-sm truncate">Hang out at the rook.fm lounge</p>
                </div>
                <div class="grow min-w-64 overflow-y-auto relative">
                    <div ref="base" class="flex flex-col space-y-0.5 p-2 absolute inset-0 overflow-y-auto">
                        <p v-if="chat.length === 0" class="text-lg opacity-50 font-bold text-center p-8">Start a new conversation!</p>
                        <div v-for="(post, index) in chat" class="flex flex-col w-full" :class="{'self-end items-end': post.type === 'SEND'}">
                            <div v-if="index === 0 || new Date(chat[index - 1]?.created_at).toDateString() !== new Date(post.created_at).toDateString()" class="flex items-center space-x-2 w-full">
                                <span class="border-t w-full"></span>
                                <span class="text-center text-nowrap text-xs">{{ useDateFormat(post.created_at, 'MMMM D, YYYY').value }}</span>
                                <span class="border-t w-full"></span>
                            </div>
                            <span v-if="chat[index - 1]?.user !== post.user || new Date(chat[index - 1]?.created_at).toDateString() !== new Date(post.created_at).toDateString()" class="text-xs font-bold">{{ post.user }}</span>
                            <div class="flex items-center gap-1 group chatroom-bubble" :class="{'flex-row-reverse': post.type === 'SEND'}">
                                <p :class="{'italic text-gray-500': post.type === 'SYSTEM', '!bg-yellow-400': post.type === 'SEND'}" class="px-2 py-1 mt-0.5 rounded-md w-fit bg-blue-200 break-all">{{ post.content }}</p>
                                <span class="text-xs group-hover:visible invisible">{{ useDateFormat(new Date(post.created_at), 'h:mm A').value }}</span>
                            </div>
                        </div>
                        <div class="py-1"></div>
                    </div>
                </div>
                <div v-if="isAuthenticated()" class="flex items-stretch sticky bottom-0 border-t chatroom-footer p-1 space-x-1">
                    <input v-model="msg" @keydown="keypress" class="chatbox grow border-none bg-transparent" type="text" placeholder="Say something nice..."/>
                    <button :disabled="sending" class="px-2 py-1" @click="sendMessage">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                            <path d="M3.105 2.288a.75.75 0 0 0-.826.95l1.414 4.926A1.5 1.5 0 0 0 5.135 9.25h6.115a.75.75 0 0 1 0 1.5H5.135a1.5 1.5 0 0 0-1.442 1.086l-1.414 4.926a.75.75 0 0 0 .826.95 28.897 28.897 0 0 0 15.293-7.155.75.75 0 0 0 0-1.114A28.897 28.897 0 0 0 3.105 2.288Z" />
                        </svg>
                    </button>
                </div>
                <div class="text-center border-t p-2 bg-white/50" v-else>
                    <span><Link :href="route('register')" class="underline">Sign up</Link> to chat with others</span>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-48 select-none" v-if="openChannels">
            <span class="text-sm font-bold px-2 pt-1">ONLINE &mdash; 14</span>
            <div class="flex items-center space-x-1 px-2 py-1.5 hover:bg-white/25 cursor-pointer">
                <div class="rounded-full p-4 bg-blue-500"></div>
                <span>Name</span>
            </div>
        </div>
    </div>
</template>
