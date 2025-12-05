<script setup>
import {Link, useForm} from "@inertiajs/vue3";
import {isAuthenticated} from "@/util.js";

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
<template>
    <div class="flex flex-col">
        <p class="text-center px-4 py-2">Welcome to rook.fm, your one stop shop for non-stop funk!<br/>Hang out in the chat, join a fan club, or just see what's on the radio.</p>
        <div v-if="!isAuthenticated()" class="flex space-x-4 p-4">
            <div class="rounded-full p-2 bg-gradient-to-b from-gray-400 to-gray-600 h-fit text-white">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-48">
                    <path d="M10 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.465 14.493a1.23 1.23 0 0 0 .41 1.412A9.957 9.957 0 0 0 10 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 0 0-13.074.003Z" />
                </svg>
            </div>
            <div class="flex flex-col space-y-2">
                <h1 class="text-xl font-bold">Log In</h1>

                <span class="text-sm">Email</span>
                <input type="email" v-model="form.email" class="!mt-0"/>

                <span class="text-sm">Password</span>
                <input type="password" v-model="form.password" class="!mt-0"/>

                <div class="flex items-center justify-between space-x-4">
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" v-model="form.remember"/>
                        <span>Remember Me</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        <Link class="underline text-sm">Sign Up</Link>
                        <button class="bg-neutral-700/50 rounded-md w-fit px-2 py-1" @click="submit">Log In</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
