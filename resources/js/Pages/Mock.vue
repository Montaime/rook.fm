<script setup>
import vista from "@/../assets/vista.jpg"
import {onMounted, ref} from "vue";
import satori from "satori";
import { html } from "satori-html";

const x = ref(null);

onMounted(async () => {
    const robotoArrayBuffer = await (await fetch('https://fonts.bunny.net/roboto/files/roboto-latin-400-normal.woff')).arrayBuffer();

    let element = document.createElement('a');
    element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(await satori(html(x.value.innerHTML), {
        width: 600,
        height: 400,
        fonts: [
            {
                name: 'Roboto',
                data: robotoArrayBuffer,
                weight: 400,
                style: 'normal',
            },
        ],
    })));
    element.setAttribute('download', 'testy.svg');

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
})
</script>
<template>
    <div class="flex p-4 h-screen bg-cover" :style="`background-image:url(${vista});`">
        <div ref="x" class="flex w-full h-fit text-white border border-black shadow-xl bg-gradient-to-b from-black/10 via-white/80 via-30% to-white/10 to-[31%] rounded-md backdrop-blur-md">
            <div class="flex flex-col w-full relative border border-white/40 rounded-md p-2 pt-0 bg-gradient-to-b from-blue-950/80 via-blue-900/20 via-30% to-black/0 to-[31%]">
                <div class="flex justify-self-end w-fit border border-t-0 border-white/40 rounded-md rounded-t-none">
                    <div class="flex relative text-xs w-fit border border-t-0 border-black/40 rounded-md rounded-t-none">
                        <span class="border border-white/20 rounded-bl px-2">
                            s
                        </span>
                        <span class="border-l border-black/40"></span>
                        <span class="border border-white/20 px-2">
                            m
                        </span>
                        <span class="border-l border-black/40"></span>
                        <span class="border border-white/20 rounded-br px-2 bg-red-950">
                            xxx
                        </span>
                        <span class="z-10 absolute inset-0 rounded-b-md" style="background:linear-gradient(to bottom, rgba(255,255,255,0.4) 0%, rgba(255,255,255,0) 30%, rgba(255,255,255,0) 80%, rgba(255,255,255,0.4) 100%);">

                        </span>
                    </div>
                </div>
                <div class="flex border mt-2 border-white/40 rounded">
                    <div class="flex w-full justify-between border border-black/50 rounded">
                        <div class="flex flex-col bg-gradient-to-b from-[#1e3040] to-[#050019] w-1/3 rounded-l">
                            <p class="px-4 py-2">hello</p>
                            <div class="flex flex-col">
                                <span class="bg-gradient-to-r from-transparent via-white/50 to-transparent pt-[1px]"></span>
                                <span class="bg-gradient-to-b px-4 py-2">Hello Bro</span>
                                <span class="bg-gradient-to-r from-transparent via-white/50 to-transparent pt-[1px]"></span>
                            </div>
                            <p class="px-4 py-2">hello</p>
                        </div>
                        <div class="flex flex-col bg-white rounded-r w-full">
                            <div class="flex space-x-4 rounded-tr border-b border-white/40 bg-gradient-to-b from-[#505e6f] via-[#505e6f] via-50% to-blue-950 to-[51%] px-4">
                                <span>Addresses</span>
                                <span>Receive</span>
                                <span>Send</span>
                            </div>
                            <div class="flex flex-col text-black p-2">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
