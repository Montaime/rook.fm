<script setup>
import {ref, watch} from "vue";

const card = ref(null);
const code = ref('');
const subtitle = ref('');

const toggle = () => {
    if (card.value.classList.contains('card')) {
        card.value.classList.remove('card')
    } else {
        card.value.classList.add('card')
    }
}

watch(code, (nv, ov) => {
    if (nv.startsWith('+1')) {
        card.value.classList.add('card')
        subtitle.value = 'call me <3';
    } else {
        card.value.classList.remove('card')
    }

    if (nv.startsWith('0')) {
        card.value.classList.add('console')
        subtitle.value = 'booting up...';
    } else {
        card.value.classList.remove('console')
    }

    if (nv.toLowerCase().startsWith('mere')) {
        card.value.classList.add('mere')
        subtitle.value = ':3';
    } else {
        card.value.classList.remove('mere')
    }
})
</script>
<template>
    <div class="flex flex-col items-center bg-gray-200 min-h-screen overflow-y-auto p-8">
        <div ref="card" class="relative z-0 transition-all font-mono relative flex flex-col justify-between h-48 bg-gray-300 rounded-lg p-8 aspect-video">
            <h2 class="label transition-all text-3xl text-gray-700 top-0">Montaime&nbsp;Club</h2>
            <span class="z-10 absolute transition-all opacity-0 subtitle">{{ subtitle }}</span>
            <div class="flex items-center input w-full">
                <input v-model.trim="code" @change="test" type="text" class="transition-all bg-white w-full border-0 border-b-4 border-gray-500"/>
            </div>
        </div>
        <button class="mt-8 bg-white px-2 py-1 rounded" @click="toggle">Toggle</button>
    </div>
</template>
<style>
.card {
    @apply rounded-none relative bg-white rotate-[2deg] !font-written
}

.console {
    @apply bg-black
}

.mere {
    @apply bg-red-200
}

.console > .label {
    @apply text-green-500
}

.console input {
    @apply border-none bg-black text-green-500
}

.console > .input {
    @apply before:content-['$'] text-green-500
}

.console input:focus-visible {
    @apply ring-0
}

.console > .subtitle {
    @apply whitespace-nowrap opacity-100 text-green-500 top-4
}

.card input {
    @apply border-b-2 text-red-500
}

.card > .label {
    @apply text-xl text-red-500
}

.card > .subtitle {
    position: absolute;
    top: 3.5rem;
    left: 4rem;
    opacity: 100;
    color: red;
    white-space: nowrap;
    font-size: 3rem;
    transform: rotateZ(-5deg);
}

.card input:focus-visible {
    @apply ring-0 border-red-500
}

.card {
    mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/23618/rip.svg');
    mask-size: 16rem;
}
</style>
