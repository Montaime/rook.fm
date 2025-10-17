<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FormButton from "@/Components/FormButton.vue";
import {useForm} from "@inertiajs/vue3";
import {useDateFormat} from "@vueuse/core";

const props = defineProps({
    feedback: Object
});
</script>
<template>
    <AdminLayout>
        <div v-for="comment in feedback" class="pane">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="uppercase text-xs px-2 py-0.5 rounded font-bold text-white bg-neutral-800">#{{ comment.id }}</div>
                    <h2 class="text-xl font-bold mx-2">{{ comment.subject }}</h2>
                </div>
                <div class="uppercase text-xs px-1 py-0.5 rounded font-bold text-white bg-neutral-800">{{ comment.type }}</div>
            </div>
            <p>{{ comment.description }}</p>
            <div class="flex items-center">
                <span class="opacity-50 text-sm mr-1">{{ useDateFormat(comment.created_at, 'MMM D YYYY hh:mm A').value }}</span>
                <span>&middot; {{ comment.name }}</span>
                <span v-if="comment.user_id" class="digit ml-1">(ID:{{ comment.user_id }})</span>
            </div>
            <details class="text-xs">
                <summary class="cursor-pointer">Additional Data</summary>
                <textarea readonly class="w-full border-0 bg-black text-white font-mono text-xs rounded-md">{{ comment.data }}</textarea>
            </details>
        </div>
    </AdminLayout>
</template>
