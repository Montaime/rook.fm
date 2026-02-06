<script setup>
import Logo from '../Components/Logo.vue';
</script>
<template>
    <div class="flex flex-col items-center p-4 select-none">
        <Logo/>
        <h2>Web Operating System & Funk Delivery Center</h2>
        <span class="bg-black rounded px-1 text-white text-sm font-mono text-center select-text">{{ $os.VERSION }}{{ !$os.environment.PROD ? '-' + $os.environment.MODE : '' }}</span>
    </div>
</template>
