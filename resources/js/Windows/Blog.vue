<script setup>
import {onBeforeMount, ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";
import {useForm} from "@inertiajs/vue3";
import TipTap from "./../Components/TipTap.vue";
import {useDateFormat} from "@vueuse/core";

const blog = ref([]);

const currentPost = ref(null);
const editing = ref(false);

const newPost = useForm({
    title: '',
    content: '',
    blurb: '',
    scheduled: false,
    published_at: null,
    files: []
})

const submit = () => {
    newPost.post('/posts/new', {
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
    fetch('/clubs').then(res => res.json()).then(data => blog.value = data.posts)
}

onBeforeMount(() => {
    refreshPosts()
})

const isImage = (path) => {
    let bits = path.split('.');

    let exts = ['png', 'gif', 'jpg', 'jpeg'];

    if (exts.includes(bits[bits.length - 1])) return true;
}

const isAudio = (path) => {
    let bits = path.split('.');

    let exts = ['mp3', 'wav'];

    if (exts.includes(bits[bits.length - 1])) return true;
}

const isVideo = (path) => {
    let bits = path.split('.');

    let exts = ['mp4'];

    if (exts.includes(bits[bits.length - 1])) return true;
}

const filename = (path) => {
    let bits = path.split('/');
    return bits[bits.length - 1];
}
</script>
<template>
    <div class="flex justify-between items-center">
        <h2 class="font-bold text-2xl">Mere Notilde Club</h2>
        <div class="flex space-x-2">
            <div v-if="isAuthenticated() && [1, 10].includes(getUser().id)" @click="editing = true" class="underline w-fit cursor-pointer">New Post</div>
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
    <div v-else-if="currentPost !== null" class="flex flex-col items-center">
        <span @click="currentPost = null" class="w-fit cursor-pointer underline italic">Back</span>
        <h2 class="font-bold text-2xl">{{ blog[currentPost].title }}</h2>
        <TipTap :editable="false" v-model="blog[currentPost].content"/>
        <details v-if="blog[currentPost].files.length > 0" class="flex flex-col items-center space-y-2 border-2 border-neutral-100 p-2 w-full">
            <summary class="font-bold text-lg uppercase cursor-pointer">Attachments</summary>
            <div v-for="file in blog[currentPost].files" class="flex flex-col space-y-2">
                <span class="text-center bg-black rounded px-1 py-0.5 text-white font-mono">{{ filename(file) }}</span>
                <img class="max-h-64 max-w-64 w-auto h-auto block" v-if="isImage(file)" :src="'storage/' + file" alt=""/>
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
        <div class="py-16" v-if="blog.length === 0">
            <p>There are currently no posts</p>
        </div>
    </div>
</template>
