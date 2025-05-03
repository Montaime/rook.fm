<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {getUser} from "@/util.js";

const editing = ref(false);

const newPost = useForm({
    name: '',
    type: '',
    data: '',
    reproduction: '',
})

const submitted = ref(false);

const submit = () => {
    newPost.post('/posts/new', {
        onSuccess: () => {
            newPost.type = '';
            newPost.data = '';

            submitted.value = true;
        },
        onError: (e) => {
            console.error(e);
            window.alert('Some issue occurred. Check console for detailed error.');
        }
    })
}
</script>
<template>
    <h2 class="font-bold text-4xl">Feedback/Bugs</h2>
    <div v-if="submitted" class="y space-y-2 items-center w-full text-center">
        <p>Thanks for submitting your feedback!</p>
        <button @click="submitted = false" class="w-full text-center underline italic">Submit Another</button>
    </div>
    <div v-else class="y space-y-2 items-center w-full text-center">
        <h2>Got any suggestions? Maybe found a bug or two that needs squashing? Let us know!</h2>
        <!--    <input type="text" placeholder="Your Name"/>-->
        <input type="text" readonly :value="getUser().name" class="bg-white/50 w-full"/>
        <select v-model="newPost.type" class="w-full">
            <option selected hidden value="">What is the nature of your inquiry?</option>
            <option value="bug">I am reporting a bug</option>
            <option value="suggestion">I am making a suggestion</option>
        </select>
        <textarea rows="10" placeholder="What's the deal?" class="resize-none w-full"></textarea>
        <label v-if="newPost.type === 'bug'" class="flex space-x-2 items-center w-full">
            <input type="checkbox"/>
            <span>I understand I will also submit information such as my browser and operating system to help the dev team with testing</span>
        </label>
        <button @click="submitted = true" class="w-full text-center underline italic">Submit</button>
    </div>
</template>
