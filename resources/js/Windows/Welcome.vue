<script setup>
import {isAuthenticated, openW} from "@/util.js";
import Logo from "@/Components/Logo.vue";
</script>
<template>
    <div class="flex flex-col items-center p-2">
        <Logo/>
        <p class="text-center px-4 py-2">Welcome to rook.fm, your one stop shop for non-stop funk!<br/>Hang out in the chat, join a fan club, or just see what's on the radio.</p>
        <p v-if="!isAuthenticated()"><span @click="openW('Account')" class="underline">Log In</span></p>
    </div>
</template>
