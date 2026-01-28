<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {useForm} from "@inertiajs/vue3";
import {useFileDialog} from "@vueuse/core";

const props = defineProps({
    club: Object
})

const form = useForm({
    avatar: null,
});

const { open, onChange } = useFileDialog({
    accept: 'image/jpeg',
    multiple: false
});

onChange((files) => {
    form.avatar = files[0];
    form.post('/admin/club/' + props.club.id, {
        onSuccess: () => {
            form.reset()
        }
    })
})
</script>
<template>
    <AdminLayout>
        <h1 class="text-4xl font-bold">{{ club.name }}</h1>
        <div class="pane">
            <div class="size-10 rounded-full bg-cover bg-neutral-500" :style="`background-image: url('/storage/clubs/${club.id}/icon.jpg');`"></div>
            <button @click="open">Upload Avatar</button>
        </div>
    </AdminLayout>
</template>
