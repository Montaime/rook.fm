<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {Link, useForm} from "@inertiajs/vue3";
import TipTap from "@/Components/TipTap.vue";
import FormButton from "@/Components/FormButton.vue";

const props = defineProps({
    clubs: Object
});

const newClub = useForm({
    name: '',
    description: '',
    owner_id: 0,
});

const submitClub = () => {
    newClub.post('/admin/clubs/new', {
        onSuccess: () => {
            newClub.reset();
        }
    });
}

const newPost = useForm({
    name: '',
    description: '',
    owner_id: 0,
});
</script>
<template>
    <AdminLayout>
        <details class="pane">
            <summary>Create Club</summary>
            <div class="flex flex-col space-y-2">
                <input v-model="newClub.name" type="text" placeholder="club name"/>
                <input v-model="newClub.description" type="text" placeholder="description"/>
                <input v-model="newClub.owner_id" type="number" placeholder="owner"/>
                <FormButton :callback="submitClub" :form="newClub">Create</FormButton>
            </div>
        </details>
        <details class="pane">
            <summary>Create Post</summary>
            <div class="flex flex-col space-y-2">
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
            </div>
        </details>
        <div class="pane">
            <table class="w-full table-auto border-neutral-300">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Owner</th>
                    <th>Posts</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="club in clubs">
                    <td class="digit">{{ club.id }}</td>
                    <td>{{ club.name }}</td>
                    <td>{{ club.description }}</td>
                    <td><span class="digit">{{ club.owner_id }}</span> {{ club.owner.name }}</td>
                    <td>{{ club.posts_count }}</td>
                    <td>
                        <Link :href="`/admin/club/${club.id}`">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                                <path d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z" />
                            </svg>
                        </Link>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
<style scoped>

</style>
