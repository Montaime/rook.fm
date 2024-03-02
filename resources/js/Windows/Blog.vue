<script setup>
import {onBeforeMount, ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";
import {useForm} from "@inertiajs/vue3";

const blog = ref([]);

const currentPost = ref(null);
const editing = ref(false);

const newPost = useForm({
    title: '',
    content: '',
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

            refreshPosts()
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
</script>
<template>
    <div class="flex justify-between items-center">
        <h2 class="font-bold text-2xl">Mere Notilde Club</h2>
        <div class="flex space-x-2">
            <div v-if="isAuthenticated() && getUser().id === 1" @click="editing = true" class="underline w-fit cursor-pointer">New Post</div>
            <div @click="refreshPosts" class="underline w-fit cursor-pointer">Refresh</div>
        </div>
    </div>
    <div class="border-t border-gray-400"></div>
    <div v-if="editing" class="flex flex-col space-y-2">
        <h2 class="font-bold text-2xl">Share New Post</h2>
        <input v-model="newPost.title" type="text" placeholder="Post Title"/>
        <label>
            <input v-model="newPost.scheduled" type="checkbox" class="pr-2"/>
            Schedule Post
        </label>
        <input v-if="newPost.scheduled" v-model="newPost.published_at" type="datetime-local"/>
        {{newPost.files}}
        <input @input="newPost.files = $event.target.files" type="file" multiple/>
        <textarea v-model="newPost.content" placeholder="Do something amazing..."></textarea>
        <div @click="submit" class="underline">Submit</div>
        <span @click="editing = false" class="w-fit cursor-pointer underline italic">Back</span>
    </div>
    <div @click="currentPost = null" v-else-if="currentPost !== null" class="flex flex-col items-center">
        <span @click="currentPost = null" class="w-fit cursor-pointer underline italic">Back</span>
        <h2 class="font-bold text-2xl">{{ blog[currentPost].title }}</h2>
        <div v-html="blog[currentPost].content"></div>
        <span @click="currentPost = null" class="w-fit cursor-pointer underline italic">Back</span>
    </div>
    <div v-else class="flex flex-col divide-y">
        <div v-for="(post, key) in blog" class="leading-0 pb-1">
            <h1 class="font-bold text-lg">{{ post.title }}</h1>
            <p>{{ post.content.substring(0, 100) }} <span @click="currentPost = key" class="cursor-pointer underline italic">Read More</span></p>
        </div>
        <div v-if="false && blog.length === 0">
            <p>There are currently no posts</p>
        </div>
    </div>
</template>
