<script setup>
import {useForm} from "@inertiajs/vue3";

const props = defineProps({

})

const form = useForm({
    name: '',
    description: '',
    owner_id: 0,
})

const submit = () => {
    form.post('', {})
}
</script>
<template>
    <div>
        <form @submit.prevent="submit" class="flex flex-col space-y-4 p-2">
            <input v-model="form.name" type="text" placeholder="club name"/>
            <input v-model="form.description" type="text" placeholder="description"/>
            <input v-model="form.owner_id" type="number" placeholder="owner"/>
            <input type="submit"/>
        </form>
    </div>
</template>
