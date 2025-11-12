<script setup>
import {onMounted, ref, useTemplateRef} from "vue";

const canvas = useTemplateRef('canvas');

let ctx = null;

let init = true;
let raindrops = [];
const pieces = '♚♛♜♝♞♟'.split('');

const fontSize = 20;

const render = () => {
    let c = canvas.value;

    if (!c) {
        setTimeout(render, 50);

        return;
    }

    if (c.height !== c.clientHeight || c.width !== c.clientWidth) {
        c.height = c.clientHeight;
        c.width = c.clientWidth;

        raindrops = new Array(Math.floor(canvas.value.width / fontSize)).fill(canvas.value.height)
        ctx = canvas.value.getContext('2d');

        init = false;
    }

    if (!ctx) {
        setTimeout(render, 50);

        return;
    }

    ctx.fillStyle = '#00000030';
    ctx.fillRect(0, 0, c.width, c.height);

    ctx.font = fontSize + 'px Arial';
    ctx.fillStyle = '#0f0';

    for (let i = 0; i < raindrops.length; i++) {

        ctx.fillText(
            pieces[Math.floor(Math.random() * pieces.length)],
            i * fontSize,
            raindrops[i] * fontSize
        );

        if (++raindrops[i] * fontSize > c.height && Math.random() > 0.96) raindrops[i] = 0;
    }

    setTimeout(render, 50);
}

const step = ref(0);
const log = ref('\xa0');
const input = ref('');

const tryCode = () => {
    if (input.value.toLowerCase() === 'oddqueer') {
        input.value = '';
        log.value = 'Poetically \'Odd Queer\' Vinyl Exclusive Preorder';
        step.value++;
    } else {
        log.value = 'Invalid code, try again';
    }
}

const finish = (e) => {
    log.value = 'Thanks! Check your email.';
    step.value = 2;
}

const restart = () => {
    log.value = '\xa0';
    step.value = 0;
}

onMounted(() => {
    render();
});
</script>
<template>
    <div class="flex flex-col justify-end relative">
        <div class="mx-6 mb-6 mt-40 relative z-10 border-2 border-black border-b-neutral-300 border-r-neutral-500">
            <div class="flex flex-col items-center p-2 bg-neutral-700/75 border-2 border-black border-t-neutral-400 border-l-neutral-500 backdrop-blur-sm">
                <span class="label">Log</span>
                <p class="emboss whitespace-nowrap text-sm py-0.5 px-4 w-full text-center select-none">- TEAM ROOK2ROOK PRESENTS -</p>
                <p class="emboss text-sm py-0.5 px-4 mt-1 w-full text-center select-none">{{ log }}</p>

                <template v-if="step === 0">
                    <span class="label">Enter your code</span>
                    <input v-model="input" class="py-0.5 px-2 emboss w-full" type="text" placeholder="Code"/>

                    <button @click="tryCode" class="bg-neutral-300 border border-white px-2 mt-1">Submit</button>
                </template>

                <form @submit="finish" v-show="step === 1" action="https://music.us12.list-manage.com/subscribe/post?u=ffa46154083e44e2f45c73e9b&amp;id=220a7219e2&amp;f_id=00c68fe3f0"  method="post" target="_blank">
                    <span class="label">Enter your email</span>

                    <input type="email" name="EMAIL" class="py-0.5 px-2 emboss w-full" placeholder="Email" required>

                    <span class="label">Customization</span>
                    <div class="text-white">
                        <label>
                            <input type="checkbox" name="group[61940][1]">
                            <span>Keep me updated for future Event Horizon parties</span>
                        </label>
                        <label>
                            <input type="checkbox" name="group[61940][2]">
                            <span>Keep me updated on upcoming FIBRE releases and shows</span>
                        </label>
                        <label>
                            <input type="checkbox" name="group[61940][4]">
                            <span>Keep me updated on all Montaime releases and shows</span>
                        </label>
                    </div>

                    <input type="submit" class="bg-neutral-300 border border-white px-2 mt-1" value="Submit">
                </form>

                <button v-if="step === 2" @click="restart" class="bg-neutral-300 border border-white px-2 mt-1">Restart</button>
            </div>
        </div>
        <div class="absolute inset-0">
            <canvas @resize="init = true" class="size-full bg-neutral-900" ref="canvas"></canvas>
        </div>
    </div>
</template>
<style scoped>
.label {
    @apply text-sm w-full text-white mt-2 select-none;
}

div > label {
    @apply flex items-center space-x-1.5;
}

.emboss {
    @apply bg-neutral-300 border-2 border-white text-sm focus:ring-0 focus:border-white border-t-neutral-500 border-l-neutral-500 focus:border-t-neutral-500 focus:border-l-neutral-500;
}
</style>
