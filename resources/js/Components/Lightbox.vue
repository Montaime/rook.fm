<script setup>
import {ref} from "vue";

const open = ref(false)
</script>
<template>
    <div>
        <div class="cursor-pointer" @click="open = true">
            <slot name="trigger"/>
        </div>
        <teleport to="body">
            <div @click="open = false" v-if="open" class="flex flex-col items-center justify-center absolute inset-0 bg-black/50">
                <div @click.stop class="flex flex-col items-center w-fit h-fit">
                    <slot/>
                </div>
            </div>
        </teleport>
    </div>
</template>
