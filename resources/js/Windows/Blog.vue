<script setup>
import {computed, onBeforeMount, ref, useTemplateRef} from "vue";
import {getUser, isAdmin, isAuthenticated} from "../util.js";
import {useForm} from "@inertiajs/vue3";
import TipTap from "./../Components/TipTap.vue";
import {useDateFormat, useDropZone} from "@vueuse/core";
import {Link} from "@inertiajs/vue3";
import route from "ziggy-js";
import InputError from "@/Components/InputError.vue";
import FileList from "@/Components/FileList.vue";

const blog = ref([]);
const info = ref({
    name: 'Join a club'
});

const currentPost = ref(null);
const currentBlog = ref(-1);
const editing = ref(false);
const loading = ref(true);

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

    loading.value = true;

    fetch('/club/' + currentBlog.value).then(res => res.json()).then(data => {
        info.value = data;
        blog.value = data.posts;
        loading.value = false;
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
const isAudio = (path) => extCheck(path, ['mp3', 'wav', 'ogg'])
const isVideo = (path) => extCheck(path, ['mp4'])
const canPreview = ({path}) => isImage(path) || isAudio(path) || isVideo();
const extCheck = (path, extensions) => extensions.includes(path.split('.').pop().toLowerCase());

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
};

const { isOverDropZone } = useDropZone(useTemplateRef('dropzone'), {
    multiple: true,
    onDrop: (files) => {
        newPost.files.push(...files)
    }
});

const switchBlog = (id) => {
    editing.value = false;
    currentPost.value = null;
    info.value = {};
    if (id === -1) info.value.name = 'Join a club';
    blog.value = [];
    currentBlog.value = id;
    refreshPosts();
}
</script>
<template>
    <div class="flex h-full">
        <aside class="flex flex-col border-r bg-neutral-100/25 w-52 shrink-0">
            <div class="relative grow">
                <div class="absolute inset-0 overflow-y-auto">
                    <div v-for="club in clubs" @click="switchBlog(club.id)" class="flex items-center space-x-2 px-2 py-1 cursor-pointer" :class="{'bg-neutral-100/50': club.id === currentBlog}">
                        <div class="size-10 rounded-full bg-blue-500"></div>
                        <span class="text-lg">{{ club.name }}</span>
                    </div>
                </div>
            </div>
            <div @click="switchBlog(-1)" class="flex items-center space-x-2 p-2 cursor-pointer border-t" :class="{'bg-neutral-100/50 font-bold': currentBlog === -1}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="text-lg">Join a Club</span>
            </div>
        </aside>
        <div class="flex flex-col grow">
            <div class="flex space-x-2 justify-between items-center p-2 border-b bg-neutral-100/25 text-nowrap">
                <h2 class="text-2xl font-bold">{{ editing ? 'New Post' : info?.name ?? '' }}</h2>
                <button class="px-2 py-1 rounded bg-neutral-100/50" @click="editing = false" v-if="editing">Back</button>
                <button class="px-2 py-1 rounded bg-neutral-100/50" @click="currentPost = null" v-else-if="currentPost !== null">Back</button>
                <div v-else-if="currentBlog !== -1" class="flex space-x-2">
                    <button v-if="isAuthenticated() && (getUser().id === info?.owner_id || isAdmin())" class="px-2 py-1 rounded bg-neutral-100/50" @click="editing = true">New Post</button>
                    <button @click="refreshPosts" class="px-2 py-1 rounded bg-neutral-100/50">Refresh</button>
                </div>
            </div>
            <div class="relative grow">
                <div class="absolute inset-0 overflow-y-auto">
                    <div v-if="editing" class="flex flex-col space-y-2 p-2">
                        <input v-model="newPost.title" type="text" placeholder="Post Title"/>
                        <input v-model="newPost.blurb" type="text" placeholder="Short Blurb"/>
                        <label>
                            <input v-model="newPost.scheduled" type="checkbox" class="pr-2"/>
                            Schedule Post
                        </label>
                        <input v-if="newPost.scheduled" v-model="newPost.published_at" type="datetime-local"/>
                        <label ref="dropzone" class="flex flex-col items-center border-dashed border-2 p-4 rounded-lg select-none cursor-pointer bg-neutral-100/25" :class="{'!bg-neutral-400/25': isOverDropZone}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span>Drag and drop or click here to add files</span>
                            <input @change="newPost.files.push(...$event.target.files)" type="file" class="hidden" multiple/>
                        </label>
                        <FileList :list="newPost.files" :editable="true"/>
                        <TipTap v-model="newPost.content" :editable="true"/>
                        <details>
                            <summary class="cursor-pointer text-xs">View Source</summary>
                            <textarea v-model="newPost.content" class="w-full whitespace-pre text-xs bg-black/50 text-white rounded" readonly></textarea>
                        </details>
                    </div>
                    <div v-else-if="currentPost !== null && currentBlog !== -1" class="flex flex-col items-center max-h-[75vh] p-2">
                        <h2 class="font-bold text-2xl">{{ blog[currentPost].title }}</h2>
                        <TipTap :editable="false" v-model="blog[currentPost].content"/>
                        <details v-if="blog[currentPost].files.length > 0" class="flex flex-col items-center space-y-2 border-2 border-neutral-100 p-2 w-full">
                            <summary class="font-bold text-lg uppercase cursor-pointer w-full">Attachments</summary>
                            <div v-for="file in blog[currentPost].files" class="flex flex-col space-y-2 w-full">
                                <span v-if="canPreview(file)" class="truncate text-center text-xs text-wrap bg-black rounded px-1 py-0.5 text-white font-mono">{{ file.name }}</span>
                                <img class="max-h-full max-w-full w-auto h-auto block" v-if="isImage(file.path)" :src="'storage/' + file.path" alt=""/>
                                <audio v-else-if="isAudio(file.path)" controls>
                                    <source :src="'storage/' + file.path" type="audio/mpeg"/>
                                </audio>
                                <video class="max-h-64 max-w-64 w-auto h-auto block" v-else-if="isVideo(file.path)" controls>
                                    <source :src="'storage/' + file.path" type="video/mp4"/>
                                </video>
                            </div>
                        </details>
                        <span class="font-bold w-full mt-2 px-2 text-xs tracking-wider uppercase">Files</span>
                        <FileList :list="blog[currentPost].files" class="w-full"/>
                    </div>
                    <div v-else class="flex flex-col divide-y p-2">
                        <div v-for="(post, key) in blog" class="leading-0 pb-1">
                            <h1 class="font-bold text-lg">{{ post.title }}</h1>
                            <p>{{ useDateFormat(post.published_at, 'MMM D YYYY h:m A').value }}</p>
                            <span v-if="new Date(post.published_at) > new Date()" class="text-xs bg-red-500 rounded-full px-1 py-0.5 text-white uppercase">Unpublished</span>
                            <p><span>{{ post.blurb }}...</span> <span @click="currentPost = key" class="cursor-pointer underline italic">Read More</span></p>
                        </div>
                        <div class="flex items-center justify-center" v-if="blog.length === 0 && currentBlog !== -1 && !loading">
                            <span>There are currently no posts</span>
                        </div>
                        <div class="text-center" v-if="currentBlog === -1">
                            <p v-if="Object.keys(clubs).length === 0">You are not a member of any artist clubs</p>
                            <div v-if="isAuthenticated()" class="flex flex-col items-center">
                                <input type="text" v-model="code.code" placeholder="xxxx-xxxx-xxxx-xxxx" class="text-center w-full"/>
                                <button @click="redeem" class="underline">Redeem Code</button>
                                <InputError class="mt-2" :message="code.errors.code" />
                            </div>
                            <p v-else><Link :href="route('register')" class="underline">Sign up</Link> to rook.fm with a creator code to join a fanclub!</p>
                        </div>
                    </div>
                </div>
            </div>
            <button v-if="editing" @click="submit" class="w-full bg-neutral-100/50 border-t p-2">Post to <span class="font-bold">{{ info.name }}</span></button>
        </div>
    </div>
</template>
