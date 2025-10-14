<script setup>
const props = defineProps({
    form: Object,
    callback: Function
});
</script>
<template>
    <div class="flex items-center space-x-2">
        <button @click="callback" :disabled="form.processing"><slot/></button>
        <div v-if="form.processing" class="size-4 border-2 border-transparent border-y-zinc-500 animate-spin rounded-full"></div>
        <span class="text-green-500" v-if="form.recentlySuccessful">Done!</span>
    </div>
</template>
