<script setup>
import {onBeforeMount, ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";
import {useDateFormat} from "@vueuse/core";

const chat = ref([]);

onBeforeMount(() => {
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
</script>
<template>
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
            <p :class="{'italic text-gray-500': post.type === 'SYSTEM', '!bg-yellow-400': post.type === 'SEND'}" class="px-2 py-1 mt-0.5 rounded-md w-fit bg-blue-200">{{ post.content }}</p>
        </div>
    </div>
    <div v-if="isAuthenticated()" class="flex items-stretch space-x-2 sticky bottom-0">
        <input v-model="msg" @keydown="keypress" class="chatbox grow" type="text" placeholder="Say something nice..."/>
        <button :disabled="sending" class="bg-white px-2 border button" @click="sendMessage">Send</button>
    </div>
</template>
