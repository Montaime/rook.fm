<script setup>
import {ref, useSlots, useTemplateRef} from "vue";
import {useResizeObserver} from "@vueuse/core";

const props = defineProps({
    images: Array
});

const slots = useSlots();

const scroller = useTemplateRef('scroller')
const page = ref(0);

const pages = ref(slots.default()[0].children.length);

const scroll = (amount = 1, behavior = 'smooth') => {
    page.value += amount;

    scroller.value.scrollTo({left: (scroller.value.clientWidth) * page.value, behavior: behavior})
}

useResizeObserver(scroller, () => {
    scroll(0, 'auto')
})
</script>
<template>
    <div class="flex items-center relative">
        <div v-if="page > 0" @click="scroll(-1)" class="absolute z-10 left-3 drop-shadow cursor-pointer text-white bg-neutral-700 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
            </svg>
        </div>
        <div ref="scroller" class="flex w-full items-start snap-x overflow-x-scroll no-scroller snap-mandatory">
            <slot/>
        </div>
        <div v-if="page < pages - 1" @click="scroll()" class="absolute z-10 right-3 drop-shadow cursor-pointer text-white bg-neutral-700 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
</template>
<style scoped>
.masked {
    mask-image: linear-gradient(to right, transparent 0%, rgba(0,0,0,0.8) 5%, rgba(0,0,0,0.8) 95%, transparent 100%);
}

.no-scroller {
    scrollbar-width: none;
}
</style>
