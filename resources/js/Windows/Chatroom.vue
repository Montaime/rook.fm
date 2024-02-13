<script setup>
import {onBeforeMount, ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";

const chat = ref([]);

onBeforeMount(() => {
    //Echo.leaveAllChannels();
    Echo.channel('chatroom')
        .listen('ChatMessageCreated', (e) => {
            //console.log(e);
            chat.value.push({
                user: e.user.name,
                content: e.crap,
                type: isAuthenticated() ? (getUser().id === e.user.id ? 'SEND' : '') : ''
            })

        });
})

const sendMessage = () => {
    if (msg.value.length === 0) return;

    axios.post('/chat/send', {
        message: msg.value
    }).then(() => msg.value = '')
}

const keypress = (e) => {
    if (e.keyCode === 13) sendMessage()
}

const msg = ref('')
</script>
<template>
    <div class="grow justify-self-end flex flex-col space-y-1">
        <div class="!hidden flex items-center justify-center !border-none">
            <span class="border-t w-full"></span>
            <span class="w-full text-center">January 1st, 2000</span>
            <span class="border-t w-full"></span>
        </div>
        <div v-if="chat.length === 0">
            <p class="text-lg opacity-50 font-bold text-center p-8">Start a new conversation!</p>
        </div>
        <div v-for="(post, index) in chat" class="flex flex-col" :class="{'self-end items-end': post.type === 'SEND'}">
            <span class="text-xs">{{ post.user }}</span>
            <p :class="{'italic text-gray-500': post.type === 'SYSTEM', '!bg-yellow-400': post.type === 'SEND'}" class="px-2 py-1 rounded-md w-fit bg-blue-200">{{ post.content }}</p>
        </div>
    </div>
    <input v-model="msg" @keydown="keypress" class="chatbox" type="text" placeholder="Say something nice..."/>
</template>
