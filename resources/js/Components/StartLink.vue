<script setup>
const props = defineProps({
    title: String,
    contentClasses: String
})
</script>
<template>
    <details class="startlink relative">
        <summary class="flex cursor-pointer items-center justify-between px-2 py-1 w-full">
            <span>{{ title }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </summary>
        <div :class="contentClasses" class="startsubmenu left-[100%] bottom-0 w-64 mx-4 absolute flex flex-col space-y-2"><slot/></div>
    </details>
</template>
