<script setup>
import logo from '@/../assets/logo_banner.png'
import {Link, useForm} from '@inertiajs/vue3'
import route from "ziggy-js";

const form = useForm({
    code: ''
});

const submit = () => form.post('/early-access');
</script>
<template>
    <div class="flex flex-col space-y-4 h-screen bg-black p-8 justify-center items-center text-gray-200">
        <div class="flex flex-col justify-end max-w-6xl w-full grow">
            <img :src="logo" alt="Rook FM">
            <h2 class="text-5xl font-bold mt-4">Reticulating Splines...</h2>
            <p class="font-xl">Your Rook.FM personal operating system is still installing critical updates.</p>
            <h2 class="text-3xl font-bold mt-4">Skip the Line</h2>
            <p class="font-xl">Got an early access code?</p>
            <div>
                <input v-model="form.code" type="text" class="border-none rounded py-0 px-1 font-mono text-black" placeholder="XXXX-XXXX-XXXX-XXXX"/>
                <button @click="submit" class="bg-transparent underline px-2">Let's Go →</button>
            </div>
            <p v-for="(e, k) in $page.props.errors" class="text-sm text-red-500">{{e}}</p>
            <p>Or <Link :href="route('login')" class="underline">log in</Link> if you have an account</p>
        </div>
        <div class="flex flex-col grow justify-end opacity-50">
            <span>Montaime Records &copy; 2024</span>
        </div>
    </div>
</template>
