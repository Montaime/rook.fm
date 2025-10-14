<script setup>
import {computed, onBeforeMount, ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";
import {useForm} from "@inertiajs/vue3";
import TipTap from "./../Components/TipTap.vue";
import {useDateFormat} from "@vueuse/core";
import {Link} from "@inertiajs/vue3";
import route from "ziggy-js";
import InputError from "@/Components/InputError.vue";

const blog = ref([]);
const info = ref([]);

const currentPost = ref(null);
const currentBlog = ref(null);
const editing = ref(false);

const newPost = useForm({
    club_id: currentBlog,
    title: '',
    content: '',
    blurb: '',
    scheduled: false,
    published_at: null,
    files: []
})

const submit = () => {
    if (currentBlog.value === null) return;

    newPost.post(`/club/${currentBlog.value}/posts/new`, {
        onSuccess: () => {
            newPost.title = '';
            newPost.content = '';
            newPost.scheduled = false;
            newPost.published_at = null;

            editing.value = false;

            setTimeout(refreshPosts, 500)
        },
        onError: (e) => {
            console.error(e);
            window.alert('Some issue occurred. Check console for detailed error.');
        }
    })
}

const refreshPosts = () => {
    if (currentBlog.value === null) return;

    if (currentBlog.value === -1) {
        info.value = [];
        blog.value = [];
        return;
    }

    fetch('/club/' + currentBlog.value).then(res => res.json()).then(data => {
        info.value = data;
        blog.value = data.posts;
    })
}

onBeforeMount(() => {
    let keys = Object.keys(clubs.value);
    if (keys.length > 0) {
        currentBlog.value = keys[keys.length - 1]|0;
        refreshPosts();
    }
})

const isImage = (path) => extCheck(path, ['png', 'gif', 'jpg', 'jpeg'])
const isAudio = (path) => extCheck(path, ['mp3', 'wav'])
const isVideo = (path) => extCheck(path, ['mp4'])
const extCheck = (path, extensions) => extensions.includes(path.split('.').pop());
const filename = (path) => path.split('/').pop();

const clubs = computed(() => {
    let list = {};
    if (isAuthenticated()) {
        getUser().memberships.map(c => list[c.id|0] = c);
        getUser().clubs.map(c => list[c.id|0] = c);
    }
    return list;
});

const code = useForm({
    code: ''
});
const redeem = () => {
    let old = [];

    for (let club in clubs.value) {
        old.push(clubs.value[club].id);
    }

    code.post('/membership/new', {
        preserveScroll: true,
        onSuccess: (e) => {
            code.code = '';
            for (let club in clubs.value) {
                if (!old.includes(clubs.value[club].id)) currentBlog.value = clubs.value[club].id
            }
        }
    })
}
</script>
<template>
    <div v-if="isAuthenticated() && Object.keys(clubs).length > 0">
        <div class="flex justify-between items-center">
            <select @change="refreshPosts" v-model="currentBlog" class="font-bold text-2xl bg-transparent border-none focus:ring-0">
                <option v-for="club in clubs" class="text-sm font-base" :value="club.id">{{ club.name }}</option>
                <option class="text-sm font-base" :value="-1">Join New Club</option>
            </select>
            <div class="flex space-x-2">
                <div v-if="isAuthenticated() && info && (getUser().id === info.owner_id)" @click="editing = true" class="underline w-fit cursor-pointer">New Post</div>
                <div @click="refreshPosts" class="underline w-fit cursor-pointer">Refresh</div>
            </div>
        </div>
        <div class="border-t border-gray-400"></div>
        <div v-if="editing" class="flex flex-col space-y-2">
            <h2 class="font-bold text-2xl">Share New Post</h2>
            <input v-model="newPost.title" type="text" placeholder="Post Title"/>
            <input v-model="newPost.blurb" type="text" placeholder="Short Blurb"/>
            <label>
                <input v-model="newPost.scheduled" type="checkbox" class="pr-2"/>
                Schedule Post
            </label>
            <input v-if="newPost.scheduled" v-model="newPost.published_at" type="datetime-local"/>
            <input @input="newPost.files = $event.target.files" type="file" multiple/>
            <TipTap v-model="newPost.content" :editable="true"/>
            <details>
                <summary class="cursor-pointer text-xs">View Source</summary>
                <textarea v-model="newPost.content" class="w-full whitespace-pre text-xs bg-black/50 text-white rounded" readonly></textarea>
            </details>
            <button @click="submit" class="underline">Submit</button>
            <span @click="editing = false" class="w-fit cursor-pointer underline italic">Back</span>
        </div>
        <div v-else-if="currentPost !== null && currentBlog !== -1" class="flex flex-col items-center overflow-y-auto overflow-x-hidden max-h-[75vh] p-2">
            <span @click="currentPost = null" class="w-fit cursor-pointer underline italic">Back</span>
            <h2 class="font-bold text-2xl">{{ blog[currentPost].title }}</h2>
            <TipTap :editable="false" v-model="blog[currentPost].content"/>
            <details v-if="blog[currentPost].files.length > 0" class="flex flex-col items-center space-y-2 border-2 border-neutral-100 p-2">
                <summary class="font-bold text-lg uppercase cursor-pointer">Attachments</summary>
                <div v-for="file in blog[currentPost].files" class="flex flex-col space-y-2 w-full">
                    <span class="truncate text-center text-xs text-wrap bg-black rounded px-1 py-0.5 text-white font-mono">{{ filename(file) }}</span>
                    <img class="max-h-full max-w-full w-auto h-auto block" v-if="isImage(file)" :src="'storage/' + file" alt=""/>
                    <audio v-else-if="isAudio(file)" controls>
                        <source :src="'storage/' + file" type="audio/mpeg"/>
                    </audio>
                    <video class="max-h-64 max-w-64 w-auto h-auto block" v-else-if="isVideo(file)" controls>
                        <source :src="'storage/' + file" type="video/mp4"/>
                    </video>
                    <a class="underline" v-else :href="'storage/' + file" download>download</a>
                </div>
            </details>
            <span @click="currentPost = null" class="w-fit cursor-pointer underline italic">Back</span>
        </div>
        <div v-else class="flex flex-col divide-y">
            <div v-for="(post, key) in blog" class="leading-0 pb-1">
                <h1 class="font-bold text-lg">{{ post.title }}</h1>
                <p>{{ useDateFormat(post.published_at, 'MMM D YYYY h:m A').value }}</p>
                <span v-if="new Date(post.published_at) > new Date()" class="text-xs bg-red-500 rounded-full px-1 py-0.5 text-white uppercase">Unpublished</span>
                <p><span>{{ post.blurb }}...</span> <span @click="currentPost = key" class="cursor-pointer underline italic">Read More</span></p>
            </div>
            <div class="py-16" v-if="blog.length === 0 && currentBlog !== -1">
                <p>There are currently no posts</p>
            </div>
            <div class="flex flex-col items-center p-4" v-if="currentBlog === -1">
                <input type="text" v-model="code.code" placeholder="xxxx-xxxx-xxxx-xxxx" class="text-center w-full"/>
                <button @click="redeem" class="underline">Redeem Code</button>
                <InputError class="mt-2" :message="code.errors.code" />
            </div>
        </div>
    </div>
    <div v-else class="flex flex-col items-center w-full text-center">
        <div>You are not a member of any artist clubs</div>
        <div class="flex flex-col items-center" v-if="isAuthenticated()">
            <p>If you have an invite code, enter it here:</p>
            <input type="text" v-model="code.code" placeholder="xxxx-xxxx-xxxx-xxxx" class="text-center w-full"/>
            <button @click="redeem" class="underline">Redeem Code</button>
            <InputError class="mt-2" :message="code.errors.code" />
        </div>
        <div v-else><Link :href="route('register')" class="underline">Sign up</Link> to rook.fm with a creator code to join a fanclub!</div>
    </div>
</template>
