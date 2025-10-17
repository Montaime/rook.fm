<script setup>
import prettyBytes from "pretty-bytes";

const props = defineProps({
    list: Object,
    editable: Boolean
});
</script>
<template>
    <div class="flex flex-col space-y-1">
        <div v-for="(file, i) in list" class="flex items-center space-x-2 bg-neutral-100/25 rounded-md px-2 py-1 text-xs">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 shrink-0">
                <path d="M3 3.5A1.5 1.5 0 0 1 4.5 2h6.879a1.5 1.5 0 0 1 1.06.44l4.122 4.12A1.5 1.5 0 0 1 17 7.622V16.5a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 16.5v-13Z" />
            </svg>
            <input v-if="editable" @keydown.enter="$event.target.blur()" @keydown.esc="$event.target.blur()" class="text-xs truncate grow bg-transparent border-none p-0" v-model="file.name"/>
            <span v-else class="grow truncate">{{ file.name }}</span>
            <span v-if="file.size" class="shrink-0">{{ prettyBytes(file.size) }}</span>
            <svg v-if="editable" @click="list.splice(i, 1)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 shrink-0 hover:text-red-500 cursor-pointer">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
            <a v-else :href="'storage/' + file.path" download>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 shrink-0">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-11.25a.75.75 0 0 0-1.5 0v4.59L7.3 9.24a.75.75 0 0 0-1.1 1.02l3.25 3.5a.75.75 0 0 0 1.1 0l3.25-3.5a.75.75 0 1 0-1.1-1.02l-1.95 2.1V6.75Z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </div>
</template>
