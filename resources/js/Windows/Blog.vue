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
import Lightbox from "@/Components/Lightbox.vue";
import Carousel from "@/Components/Carousel.vue";

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

    if (currentPost.value === null) {
        newPost.post(`/club/${currentBlog.value}/posts/new`, {
            onSuccess: () => {
                newPost.reset()
                editing.value = false;
                refreshPosts();
            },
            onError: (e) => {
                console.error(e);
                window.alert('Some issue occurred. Check console for detailed error.');
            }
        })
    } else {
        newPost.patch(`/posts/${blog.value[currentPost.value].id}/edit`, {
            onSuccess: () => {
                refreshPosts();
                newPost.reset();
                let old = currentPost.value;
                currentPost.value = null;
                setTimeout(() => {
                    editing.value = false;
                    currentPost.value = old;
                }, 100)
            },
            onError: (e) => {
                console.error(e);
                window.alert('Some issue occurred. Check console for detailed error.');
            }
        })
    }
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

const publish = (at) => {
    newPost.reset();
    newPost.title = blog.value[currentPost.value].title;
    newPost.blurb = blog.value[currentPost.value].blurb;
    newPost.content = blog.value[currentPost.value].content;
    newPost.published_at = at;

    newPost.patch(`/posts/${blog.value[currentPost.value].id}/edit`, {
        onSuccess: () => {
            newPost.reset();
            refreshPosts();
            currentPost.value = null;
        }
    });
}

const editMode = () => {
    const p = blog.value[currentPost.value];
    newPost.reset();
    newPost.title = p.title;
    newPost.blurb = p.blurb;
    newPost.content = p.content;
    newPost.published_at = new Date(p.published_at).toISOString().slice(0, 16);
    newPost.scheduled = !!p.published_at;
    editing.value = true;
}

const comment = useForm({
    body: ''
});

const sendComment = () => {
    if (currentPost.value === null) return;

    comment.post(`/post/${blog.value[currentPost.value].id}/comments/new`, {
        onSuccess: () => {
            // TODO@1: only refresh COMMENTS on CURRENT post. post might disappear or get reordered.
            comment.reset();
            refreshPosts();
        }
    })
}

const deleteComment = (id) => {
    useForm({}).delete('/comment/' + id, {
        onSuccess: () => {
            refreshPosts();
        }
    });
}
</script>
<template>
    <div class="flex h-full">
        <aside class="flex flex-col border-r ui-nav w-52 shrink-0">
            <div class="relative grow">
                <div class="absolute inset-0 overflow-y-auto">
                    <div v-for="club in clubs" @click="switchBlog(club.id)" class="flex items-center space-x-2 px-2 py-1 cursor-pointer ui-navlink" :class="{'ui-navlink-active': club.id === currentBlog}">
                        <div class="relative size-10 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="p-2 club-avatar-fallback rounded-full">
                                <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                            </svg>
                            <div class="size-10 !border-transparent rounded-full absolute inset-0 z-10 bg-cover" :style="`background-image: url('/storage/clubs/${club.id}/icon.jpg');`"></div>
                        </div>
                        <span class="text-lg">{{ club.name }}</span>
                    </div>
                </div>
            </div>
            <div @click="switchBlog(-1)" class="flex items-center space-x-2 p-2 cursor-pointer ui-navlink ui-navfooter" :class="{'ui-navlink-active font-bold': currentBlog === -1}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="text-lg">Join a Club</span>
            </div>
        </aside>
        <div class="flex flex-col grow">
            <div class="flex space-x-2 justify-between items-center p-2 border-b bg-neutral-100/25">
                <h2 class="text-2xl font-bold">{{ editing ? (currentPost === null ? 'New Post' : 'Edit Post') : (currentPost !== null ? blog[currentPost].title : info?.name ?? '') }}</h2>
                <button class="px-2 py-1 rounded bg-neutral-100/50" @click="editing = false" v-if="editing">Back</button>
                <div v-else-if="currentPost !== null" class="flex space-x-2">
                    <template v-if="isAuthenticated() && (getUser().id === info?.owner_id || isAdmin())">
                        <button v-if="blog[currentPost].published_at" class="px-2 py-1 rounded bg-neutral-100/50" @click="publish(null)">Unpublish</button>
                        <button v-else class="px-2 py-1 rounded bg-neutral-100/50" @click="publish(new Date().toISOString())">Publish</button>
                        <button class="px-2 py-1 rounded bg-neutral-100/50" @click="editMode">Edit</button>
                    </template>
                    <button class="px-2 py-1 rounded bg-neutral-100/50" @click="currentPost = null">Back</button>
                </div>
                <div v-else-if="currentBlog !== -1" class="flex space-x-2">
                    <button v-if="isAuthenticated() && (getUser().id === info?.owner_id || isAdmin())" class="px-2 py-1 rounded bg-neutral-100/50" @click="editing = true">New Post</button>
                    <button @click="refreshPosts" class="px-2 py-1 rounded bg-neutral-100/50">Refresh</button>
                </div>
            </div>
            <div class="relative grow">
                <div class="absolute inset-0 overflow-y-auto p-2">
                    <div v-if="editing" class="flex flex-col space-y-2">
                        <input v-model="newPost.title" type="text" placeholder="Post Title"/>
                        <input v-model="newPost.blurb" type="text" placeholder="Short Blurb"/>
                        <label>
                            <input v-model="newPost.scheduled" type="checkbox" class="pr-2"/>
                            Schedule Post
                        </label>
                        <input v-if="newPost.scheduled" v-model="newPost.published_at" type="datetime-local"/>
                        <label v-if="currentPost === null" ref="dropzone" class="flex flex-col items-center border-dashed border-2 p-4 rounded-lg select-none cursor-pointer bg-neutral-100/25" :class="{'!bg-neutral-400/25': isOverDropZone}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span>Drag and drop or click here to add files</span>
                            <input @change="newPost.files.push(...$event.target.files)" type="file" class="hidden" multiple/>
                        </label>
                        <FileList v-if="currentPost === null" :list="newPost.files" :editable="true"/>
                        <TipTap v-model="newPost.content" :editable="true"/>
                        <details>
                            <summary class="cursor-pointer text-xs">View Source</summary>
                            <textarea rows="4" v-model="newPost.content" class="w-full whitespace-pre text-xs bg-black/50 text-white rounded" readonly></textarea>
                        </details>
                    </div>
                    <div v-else-if="currentPost !== null && currentBlog !== -1" class="flex flex-col items-center max-h-[75vh] w-full">
                        <TipTap :editable="false" v-model="blog[currentPost].content"/>
                        <span class="font-bold text-lg uppercase cursor-pointer w-full">Attachments</span>
                        <Carousel class="w-full bg-neutral-200 rounded-md overflow-hidden flex-shrink-0">
                            <div v-for="file in blog[currentPost].files" class="flex flex-col w-full shrink-0 snap-center snap-always">
                                <div class="text-center text-xs text-white font-mono bg-black/50 w-full py-0.5 px-1">{{ file.name }}</div>
                                <Lightbox trigger-class="h-fit w-fit p-2" class="flex justify-center items-center h-full">
                                    <template #trigger>
                                        <div class="flex justify-center items-center h-full w-fit select-none">
                                            <img v-if="isImage(file.path)" class="w-auto h-auto block max-h-64 max-w-64" :src="'storage/' + file.path" alt=""/>
                                            <div v-else-if="isVideo(file.path)" class="relative">
                                                <div class="absolute inset-0 z-10"></div>
                                                <video class="max-h-64 max-w-64 w-auto h-auto block">
                                                    <source :src="'storage/' + file.path" type="video/mp4"/>
                                                </video>
                                            </div>
                                            <div v-else class="flex flex-col items-center justify-center space-y-2 p-4 bg-neutral-800 rounded-lg text-white">
                                                <svg v-if="isAudio(file.path)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                    <path fill-rule="evenodd" d="M19.952 1.651a.75.75 0 0 1 .298.599V16.303a3 3 0 0 1-2.176 2.884l-1.32.377a2.553 2.553 0 1 1-1.403-4.909l2.311-.66a1.5 1.5 0 0 0 1.088-1.442V6.994l-9 2.572v9.737a3 3 0 0 1-2.176 2.884l-1.32.377a2.553 2.553 0 1 1-1.402-4.909l2.31-.66a1.5 1.5 0 0 0 1.088-1.442V5.25a.75.75 0 0 1 .544-.721l10.5-3a.75.75 0 0 1 .658.122Z" clip-rule="evenodd" />
                                                </svg>
                                                <svg v-else-if="isVideo(file.path)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                    <path fill-rule="evenodd" d="M1.5 5.625c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v12.75c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 18.375V5.625Zm1.5 0v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-1.5A.375.375 0 0 0 3 5.625Zm16.125-.375a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5A.375.375 0 0 0 21 7.125v-1.5a.375.375 0 0 0-.375-.375h-1.5ZM21 9.375A.375.375 0 0 0 20.625 9h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5Zm0 3.75a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5a.375.375 0 0 0 .375-.375v-1.5ZM4.875 18.75a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375h1.5ZM3.375 15h1.5a.375.375 0 0 0 .375-.375v-1.5a.375.375 0 0 0-.375-.375h-1.5a.375.375 0 0 0-.375.375v1.5c0 .207.168.375.375.375Zm0-3.75h1.5a.375.375 0 0 0 .375-.375v-1.5A.375.375 0 0 0 4.875 9h-1.5A.375.375 0 0 0 3 9.375v1.5c0 .207.168.375.375.375Zm4.125 0a.75.75 0 0 0 0 1.5h9a.75.75 0 0 0 0-1.5h-9Z" clip-rule="evenodd" />
                                                </svg>
                                                <svg v-else xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                                    <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                                    <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </template>
                                    <img v-if="isImage(file.path)" class="max-h-full max-w-full w-auto h-auto block" :src="'storage/' + file.path" alt=""/>
                                    <audio v-else-if="isAudio(file.path)" controls>
                                        <source :src="'storage/' + file.path" type="audio/mpeg"/>
                                    </audio>
                                    <video v-else-if="isVideo(file.path)" class="max-h-64 max-w-64 w-auto h-auto block" controls>
                                        <source :src="'storage/' + file.path" type="video/mp4"/>
                                    </video>
                                    <div v-else class="flex flex-col items-center bg-white/75 px-4 py-2 rounded-md backdrop-blur-md">
                                        <p>This file type cannot be previewed</p>
                                        <span class="rounded bg-black font-mono text-white px-2 py-1 text-sm">{{ file.name }}</span>
                                        <a class="underline" :href="'storage/' + file.path" download>Download</a>
                                    </div>
                                </Lightbox>
                            </div>
                        </Carousel>
                        <span v-if="blog[currentPost].files.length > 0" class="font-bold w-full mt-2 px-2 text-xs tracking-wider uppercase">Files</span>
                        <FileList :list="blog[currentPost].files" class="w-full"/>
                        <span class="font-bold w-full mt-2 px-2 text-xs tracking-wider uppercase">Comments</span>
                        <div class="flex flex-col space-y-2 px-4 py-2 w-full">
                            <p v-if="blog[currentPost].comments.length === 0">No Comments</p>
                            <div v-for="comment in blog[currentPost].comments" class="rounded-md bg-neutral-100/50 px-2 py-1 clubs-comment-box">
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-1 items-center">
                                        <span class="font-bold">{{ comment.author.name }}</span>
                                        <span>&middot;</span>
                                        <span class="opacity-50 text-sm">{{ useDateFormat(comment.created_at, 'MMM D YYYY h:mm A').value }}</span>
                                    </div>
                                    <span v-if="isAuthenticated() && (getUser().id === info?.owner_id || isAdmin())" @click="deleteComment(comment.id)" class="text-red-500 underline cursor-pointer select-none">Delete</span>
                                </div>
                                <p>{{ comment.body }}</p>
                            </div>
                        </div>
                        <span class="font-bold w-full mt-2 px-2 text-xs tracking-wider uppercase">New Comment</span>
                        <textarea rows="4" v-model="comment.body" class="w-full bg-white/50 rounded" placeholder="Leave a comment..."></textarea>
                        <div class="flex justify-end mt-1 w-full">
                            <button @click="sendComment" class="rounded-md bg-white/50 w-fit px-2 py-1">Send</button>
                        </div>
                    </div>
                    <div v-else class="flex flex-col divide-y">
                        <div v-for="(post, key) in blog" class="leading-0 pb-1">
                            <h1 class="font-bold text-lg">{{ post.title }}</h1>
                            <p v-if="post.published_at">{{ useDateFormat(post.published_at, 'MMM D YYYY h:mm A').value }}</p>
                            <span v-if="!post.published_at" class="text-xs bg-amber-500 rounded-full px-1 py-0.5 text-white uppercase">Unpublished</span>
                            <span v-if="new Date(post.published_at).getTime() > Date.now()" class="text-xs bg-red-500 rounded-full px-1 py-0.5 text-white uppercase">Scheduled</span>
                            <p><span>{{ post.blurb }}...</span> <span @click="currentPost = key" class="cursor-pointer underline italic">Read More</span></p>
                        </div>
                        <div class="flex items-center justify-center" v-if="blog.length === 0 && currentBlog !== -1 && !loading">
                            <span>There are currently no posts</span>
                        </div>
                        <div class="text-center" v-if="currentBlog === -1">
                            <p v-if="Object.keys(clubs).length === 0">You are not a member of any artist clubs</p>
                            <div v-if="isAuthenticated()" class="flex flex-col space-y-2 items-center">
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
