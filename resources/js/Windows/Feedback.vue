<script setup>
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";
import {getUser, isAuthenticated} from "@/util.js";

const editing = ref(false);

const form = useForm({
    name: isAuthenticated() ? getUser().name : '',
    type: '',
    subject: '',
    description: '',
    userAgent: window.navigator.userAgent
})

const submitted = ref(false);

const submit = () => {
    form.post('/feedback/new', {
        onSuccess: () => {
            form.type = '';
            form.description = '';
            form.subject = '';

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
        <input v-model="form.name" type="text" placeholder="Name" :readonly="isAuthenticated()" class="w-full" :class="{'bg-white/50': isAuthenticated()}"/>
        <select v-model="form.type" class="w-full">
            <option selected hidden value="">What is the nature of your inquiry?</option>
            <option value="bug">I am reporting a bug</option>
            <option value="suggestion">I am making a suggestion</option>
        </select>
        <input v-model="form.subject" type="text" class="w-full" placeholder="Subject"/>
        <textarea v-model="form.description" rows="10" placeholder="What's the deal?" class="resize-none w-full"></textarea>
        <button @click="submit" class="w-full text-center underline italic">Submit</button>
    </div>
</template>
