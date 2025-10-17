<script setup>
import {computed, onMounted, ref} from 'vue'
import {templateRef, useEventListener} from '@vueuse/core'
import {$$$, WindowState} from '@/util.js'

const props = defineProps({
    title: String
})

const el = ref(null);
const window = defineModel();

const handles = [
    templateRef('dTL'),
    templateRef('dBL'),
    templateRef('dTR'),
    templateRef('dBR'),

    templateRef('dT'),
    templateRef('dB'),
    templateRef('dL'),
    templateRef('dR'),
]

const head = templateRef('el');
const content = templateRef('content');

const dragging = ref(false);
const draggingX = ref(false);
const draggingY = ref(false);
const draggingHeight = ref(false);
const draggingWidth = ref(false);

const mouseX = ref(null);
const mouseY = ref(null);

const startX = ref(null);
const startY = ref(null);
const startHeight = ref(null);
const startWidth = ref(null);

useEventListener(document, 'mousemove', (e) => {
    const w = window.value;
    const b = document.getElementById('bounding');
    const MIN_WIDTH = 300;
    const MIN_HEIGHT = 150;
    const SNAP_PADDING = 3;
    const SNAP_RELEASE_PADDING = 10;

    const deltaX = e.pageX - mouseX.value;
    const deltaY = e.pageY - mouseY.value;

    if (dragging.value) {
        if ((maximized.value || snapped.value) && (deltaX !== 0 || deltaY !== 0)) {
            let oldY = w.y;
            if (maximized.value) maximize();
            if (snapped.value) snap();
            w.x = e.pageX - w.width / 2;
            w.y = oldY;

            cacheStartTransforms();
            cacheTransforms();
        }

        w.x = startX.value + deltaX;
        w.y = startY.value + deltaY;

        // eslastic edges, might not be the best
        // if (w.x + w.width > document.body.clientWidth) {
        //     let extra = w.x + w.width - document.body.clientWidth;
        //     w.x -= extra * 0.65;
        // }

        if (e.pageX > SNAP_RELEASE_PADDING || e.pageX < b.clientWidth - SNAP_RELEASE_PADDING || e.pageY > SNAP_RELEASE_PADDING || e.pageY < b.clientHeight) {
            $$$.snap.x = e.pageX;
            $$$.snap.y = e.pageY;
            $$$.snap.height = $$$.snap.width = 0;
        }

        if (w.resizable) {
            let snapLeft = e.pageX <= SNAP_PADDING;
            let snapRight = e.pageX >= b.clientWidth - SNAP_PADDING;
            let snapTop = e.pageY <= SNAP_PADDING;
            let snapBottom = e.pageY >= b.clientHeight - SNAP_PADDING;

            if (snapLeft || snapRight || snapTop) {
                $$$.snap.y = 0;
                $$$.snap.height = b.clientHeight;
                $$$.snap.x = $$$.snap.width = b.clientWidth / 2;
            }

            if ((snapLeft || snapTop) && !snapRight) $$$.snap.x = 0;

            if (snapTop && !snapLeft && !snapRight) $$$.snap.width = b.clientWidth;

            if ((snapLeft || snapRight) && (snapTop || snapBottom)) $$$.snap.height = b.clientHeight / 2

            if (snapBottom && (snapLeft || snapRight)) $$$.snap.y = b.clientHeight / 2;
        }
    }

    if (draggingX.value) {
        w.x = Math.min(startX.value + startWidth.value - MIN_WIDTH, startX.value + deltaX);
        w.width = Math.max(MIN_WIDTH, startWidth.value - deltaX);
    }

    if (draggingY.value) {
        w.y = Math.min(startY.value + startHeight.value - MIN_HEIGHT, startY.value + deltaY);
        w.height = Math.max(MIN_HEIGHT, startHeight.value - deltaY);
    }

    if (draggingHeight.value) w.height = Math.max(MIN_HEIGHT, startHeight.value + deltaY);
    if (draggingWidth.value) w.width = Math.max(MIN_WIDTH, startWidth.value + deltaX);
});

const beginDrag = (e) => {
    const w = window.value;
    $$$.desktop.transforming = true;

    // deselect everything to prevent unintended in-browser drag and drop
    document.getSelection().removeAllRanges();

    mouseX.value = e.pageX;
    mouseY.value = e.pageY;
    startWidth.value = w.width;
    startHeight.value = w.height;
    startX.value = w.x;
    startY.value = w.y;
}

const endDrag = () => {
    const b = document.getElementById('bounding');
    const w = window.value;

    const EDGE_GAP = 32;

    if (w.y > b.clientHeight - 10) w.y = b.clientHeight - EDGE_GAP;
    if (w.y < 0) w.y = 0;

    if (w.x > b.clientWidth - EDGE_GAP) w.x = b.clientWidth - EDGE_GAP * 2;
    if (w.x + w.width < EDGE_GAP * 2) w.x = EDGE_GAP * 2 - w.width;

    if (dragging.value) {
        if ($$$.snap.height > 0 && $$$.snap.width > 0) {
            if ($$$.snap.height === b.clientHeight && $$$.snap.width === b.clientWidth) {
                maximize();
            } else {
                snap();
            }
        }

        $$$.snap.height = $$$.snap.width = 0;
    }

    $$$.desktop.transforming = false;
    dragging.value = false;
    draggingX.value = false;
    draggingWidth.value = false;
    draggingHeight.value = false;
    draggingY.value = false;
}

useEventListener(document, 'mouseup', endDrag);
useEventListener(document, 'blur', endDrag);

onMounted(() => {
    const w = window.value;
    const b = document.getElementById('bounding');
    const RANDOM_PADDING = 25;

    // TODO: resize observer for non resizable windows
    w.width = Math.max(w.resizable ? 500 : 0, content.value.scrollWidth);
    w.height = Math.max(w.resizable ? 350 : 0, content.value.scrollHeight);

    w.x = Math.floor(Math.random() * (b.clientWidth - w.width - RANDOM_PADDING * 2)) + RANDOM_PADDING
    w.y = Math.floor(Math.random() * (b.clientHeight - w.height - RANDOM_PADDING * 2)) + RANDOM_PADDING

    head.value.addEventListener('mousedown', (e) => {
        beginDrag(e);

        dragging.value = true;
    });

    if (!w.resizable) return;

    for (let h of handles) {
        h.value.addEventListener('mousedown', (e) => {
            beginDrag(e);

            if (h.value.dataset.axes.includes('L')) draggingX.value = true;
            if (h.value.dataset.axes.includes('R')) draggingWidth.value = true;
            if (h.value.dataset.axes.includes('B')) draggingHeight.value = true;
            if (h.value.dataset.axes.includes('T')) draggingY.value = true;
        });
    }
})

// const visible = ref(props.visible)

const slotStyle = computed(() => {
    return window.value.resizable ? `height: ${window.value.height - (el.value?.parentElement.clientHeight ?? 0)}px; width: ${window.value.width}px;` : '';
});

const maximized = ref(false);
const snapped = ref(false);

const snap = () => {
    const w = window.value;

    if (snapped.value) {
        transformFromCached();
    } else {
        if (!maximized.value) {
            cacheTransforms();
        }

        w.x = $$$.snap.x;
        w.y = $$$.snap.y;
        w.height = $$$.snap.height;
        w.width = $$$.snap.width;
    }

    snapped.value = !snapped.value
}

const maximize = () => {
    const b = document.getElementById('bounding');
    const w = window.value;

    if (maximized.value) {
        transformFromCached();
    } else {
        if (!snapped.value) {
            cacheTransforms();
        }

        w.x = 0;
        w.y = 0;
        w.width = b.clientWidth;
        w.height = b.clientHeight;
    }

    maximized.value = !maximized.value
}

const cacheTransforms = () => {
    const w = window.value;
    w.minimX = startX.value;
    w.minimY = startY.value;
    w.minimWidth = startWidth.value;
    w.minimHeight = startHeight.value;
}

const cacheStartTransforms = () => {
    const w = window.value;
    startX.value = w.x;
    startY.value = w.y;
    startWidth.value = w.width;
    startHeight.value = w.height;
}

const transformFromCached = () => {
    const w = window.value;
    w.x = w.minimX;
    w.y = w.minimY;
    w.width = w.minimWidth;
    w.height = w.minimHeight;
}

// TODO: fix snapped and maximized corner resizing
</script>
<template>
    <div @mousedown="window.z = ++$$$.desktop.zHighest" :style="`left: ${window.x}px; top: ${window.y}px; z-index:${window.z};`" class="window-container flex flex-col md:fixed md:w-max" :class="{'md:hidden': window.visibility !== 2}">
        <div class="window relative">
            <div v-if="window.resizable" class="z-20">
                <div ref="dTL" data-axes="TL" class="absolute -left-2.5 -top-2.5 cursor-nwse-resize p-2"></div>
                <div ref="dBL" data-axes="BL" class="absolute -left-2.5 -bottom-2.5 cursor-nesw-resize p-2"></div>
                <div ref="dTR" data-axes="TR" class="absolute -right-2.5 -top-2.5 cursor-nesw-resize p-2"></div>
                <div ref="dBR" data-axes="BR" class="absolute -right-2.5 -bottom-2.5 cursor-nwse-resize p-2"></div>
                <div ref="dT" data-axes="T" class="absolute -top-2 cursor-ns-resize w-full py-1"></div>
                <div ref="dB" data-axes="B" class="absolute -bottom-2 cursor-ns-resize w-full py-1"></div>
                <div ref="dL" data-axes="L" class="absolute -left-2 cursor-ew-resize h-full px-1"></div>
                <div ref="dR" data-axes="R" class="absolute -right-2 cursor-ew-resize h-full px-1"></div>
            </div>
            <div class="header flex justify-between select-none">
                <div ref="el" class="flex space-x-1.5 px-2 items-center grow">
                    <span class="text-sm py-1">{{ window.title }}</span>
                    <svg v-if="['Radio', 'Livestream'].includes(window.title)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                        <path d="M10.5 3.75a.75.75 0 0 0-1.264-.546L5.203 7H2.667a.75.75 0 0 0-.7.48A6.985 6.985 0 0 0 1.5 10c0 .887.165 1.737.468 2.52.111.29.39.48.7.48h2.535l4.033 3.796a.75.75 0 0 0 1.264-.546V3.75ZM16.45 5.05a.75.75 0 0 0-1.06 1.061 5.5 5.5 0 0 1 0 7.778.75.75 0 0 0 1.06 1.06 7 7 0 0 0 0-9.899Z" />
                        <path d="M14.329 7.172a.75.75 0 0 0-1.061 1.06 2.5 2.5 0 0 1 0 3.536.75.75 0 0 0 1.06 1.06 4 4 0 0 0 0-5.656Z" />
                    </svg>
                </div>
                <div class="hidden md:flex">
                    <div class="p-1 hover:bg-white/50">
                        <svg @click="window.visibility = 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M4 10a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 0 1.5H4.75A.75.75 0 0 1 4 10Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div v-if="window.resizable" class="p-1 hover:bg-white/50">
                        <svg @click="maximize" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M2 4.25A2.25 2.25 0 0 1 4.25 2h6.5A2.25 2.25 0 0 1 13 4.25V5.5H9.25A3.75 3.75 0 0 0 5.5 9.25V13H4.25A2.25 2.25 0 0 1 2 10.75v-6.5Z" />
                            <path d="M9.25 7A2.25 2.25 0 0 0 7 9.25v6.5A2.25 2.25 0 0 0 9.25 18h6.5A2.25 2.25 0 0 0 18 15.75v-6.5A2.25 2.25 0 0 0 15.75 7h-6.5Z" />
                        </svg>
                    </div>
                    <div class="p-1 hover:bg-white/50">
                        <svg @click="window.visibility = 0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                        </svg>
                    </div>
                </div>
                <div class="md:hidden p-1 hover:bg-white/50">
                    <svg @click="window.visibility = (window.visibility !== WindowState.Open ? WindowState.Open : WindowState.Minimized)" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" :class="{'rotate-90': window.visibility !== 2}">
                        <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div ref="content" class="container max-w-full min-w-full md:min-w-0" :style="slotStyle" :class="{[window.style ]: true, '!hidden': window.visibility !== 2, 'overflow-y-auto scroll': window.resizable, 'p-2': window.decoration}">
                <component :is="window.component"/>
            </div>
        </div>
    </div>
</template>
<style scoped>
.scroll {
    scrollbar-color: white transparent;
    scrollbar-width: thin;
}

.container {
    @apply relative flex flex-col space-y-2 grow w-full;
}
</style>
