<script setup>
import {onBeforeMount, ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";
import {useDateFormat} from "@vueuse/core";

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
    })

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
</script>
<template>
    <div class="flex divide-x px-2">
        <div class="flex flex-col space-y-1 p-2 pl-0" v-if="openChannels">
            <div class="px-2 py-1 rounded-md leading-tight bg-white/50">
                <h2 class="font-bold">#lounge</h2>
                <p class="text-sm">General hangout chat</p>
            </div>
            <div class="px-2 py-1 leading-tight cursor-pointer">
                <h2 class="font-bold">#radio</h2>
                <p class="text-sm">Talk about what's on the radio</p>
            </div>
        </div>
        <div class="flex flex-col grow h-64 overflow-y-auto">
            <div ref="base" class="grow justify-self-end flex flex-col space-y-1">
                <div class="!hidden flex items-center justify-center !border-none">
                    <span class="border-t w-full"></span>
                    <span class="w-full text-center">January 1st, 2000</span>
                    <span class="border-t w-full"></span>
                </div>
                <div v-if="chat.length === 0">
                    <p class="text-lg opacity-50 font-bold text-center p-8">Start a new conversation!</p>
                </div>
                <div v-for="(post, index) in chat" class="flex flex-col space-x-2" :class="{'self-end items-end': post.type === 'SEND'}">
                    <span class="text-xs"><span class="font-bold">{{ post.user }}</span> at {{ useDateFormat(new Date(post.created_at), 'HH:mm').value }}</span>
                    <p :class="{'italic text-gray-500': post.type === 'SYSTEM', '!bg-yellow-400': post.type === 'SEND'}" class="px-2 py-1 mt-0.5 rounded-md w-fit bg-blue-200 break-all">{{ post.content }}</p>
                </div>
                <div class="py-1"></div>
            </div>
            <div v-if="isAuthenticated()" class="flex items-stretch space-x-2 sticky bottom-0">
                <input v-model="msg" @keydown="keypress" class="chatbox grow" type="text" placeholder="Say something nice..."/>
                <button :disabled="sending" class="bg-white px-2 border button" @click="sendMessage">Send</button>
            </div>
        </div>
        <div class="flex flex-col grow" v-if="openChannels">
            <span class="text-sm font-bold px-2">ONLINE</span>
            <div class="flex items-center space-x-1 px-2 py-1.5 hover:bg-white/25 cursor-pointer">
                <div class="rounded-full p-3 bg-blue-500"></div>
                <span>Name</span>
            </div>
            <div class="flex items-center space-x-1 px-2 py-1.5">
                <div class="rounded-full p-3 bg-blue-500"></div>
                <span>Name</span>
            </div>
            <div class="flex items-center space-x-1 px-2 py-1.5">
                <div class="rounded-full p-3 bg-blue-500"></div>
                <span>Name</span>
            </div>
            <div class="flex items-center space-x-1 px-2 py-1.5">
                <div class="rounded-full p-3 bg-blue-500"></div>
                <span>Name</span>
            </div>
        </div>
    </div>
</template>
