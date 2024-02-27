<script setup>
import {ref} from "vue";
import {getUser, isAuthenticated} from "../util.js";
import {useForm} from "@inertiajs/vue3";

const blog = [
    {
        title: 'Welcome!',
        blurb: 'Hey everyone, welcome! Thank you for your support and I hope you enjoy the new record!',
        content: '<p>Hey everyone, welcome! Thank you for your support and I hope you enjoy the new record!<br/><br/>Make sure to stick around for some cool behind the scenes content including sketches and designs by Gaturo, Starbirb and Inesbi! Visuals are just as important as the music in my opinion, and here you can get a glimpse of all the hard work that went into creating the look of the album for yourself. We\'ll also be posting some previews for the next album, Soul Seek very soon!<br/><br/>Click around, leave a comment, and have fun!<br/>- m</p>',
        tags: []
    }
];

const currentPost = ref(null);
const editing = ref(false);

const newPost = useForm({
    title: '',
    content: '',
    scheduled: false,
    published_at: null
})

const submit = () => {
    newPost.post('/posts/new', {
        onSuccess: () => {
            newPost.title = '';
            newPost.content = '';
        },
        onError: (e) => {
            console.error(e);
            window.alert('Some issue occurred. Check console for detailed error.');
        }
    })
}
</script>
<template>
    <div class="flex justify-between items-center">
        <h2 class="font-bold text-2xl">Mere Notilde Club</h2>
        <div v-if="isAuthenticated() && getUser().id === 1" @click="editing = true" class="underline w-fit cursor-pointer">New Post</div>
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
            <p>{{ post.blurb }} <span @click="currentPost = key" class="cursor-pointer underline italic">Read More</span></p>
            <span v-for="tag in post.tags" class="rounded-full px-2 py-0.5 text-xs uppercase bg-red-500 text-white">{{ tag }}</span>
        </div>
        <div v-if="blog.length === 0">
            <p>Hey everyone, welcome! Thank you for your support and I hope you enjoy the new record!<br/><br/>Make sure to stick around for some cool behind the scenes content including sketches and designs by Gaturo, Starbirb and Inesbi! Visuals are just as important as the music in my opinion, and here you can get a glimpse of all the hard work that went into creating the look of the album for yourself. We'll also be posting some previews for the next album, Soul Seek very soon!<br/><br/>Click around, leave a comment, and have fun!<br/>- m</p>
        </div>
    </div>
</template>
