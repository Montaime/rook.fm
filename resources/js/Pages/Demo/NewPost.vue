<script setup>
import {useForm} from "@inertiajs/vue3";

const props = defineProps({

})

const form = useForm({
    title: '',
    content: '',
    owner_id: 1,
})

const submit = () => {
    form.post('', {})
}
</script>
<template>
    <div class="flex flex-col space-y-2">
        <h1 class="font-bold text-3xl">Make a New Club Post</h1>
        <form @submit.prevent="submit" class="flex flex-col space-y-4 p-2">
            <input v-model="form.title" type="text" placeholder="title"/>
            <textarea v-model="form.content" placeholder="Write something cool!"></textarea>
            <input v-model="form.owner_id" type="number" placeholder="owner"/>
            <input type="submit"/>
        </form>
    </div>
</template>
