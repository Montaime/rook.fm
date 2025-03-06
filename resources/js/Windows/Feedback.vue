<script setup>

</script>
<template>
    <h2 class="font-bold text-4xl">Feedback/Bugs</h2>
    <h2>Got any suggestions? Maybe found a bug or two that needs squashing? Let us know!</h2>
    <input type="text" placeholder="Your Name"/>
    <select>
        <option selected hidden>What is the nature of your inquiry?</option>
        <option>I am reporting a bug</option>
        <option>I am making a suggestion</option>
    </select>
    <textarea placeholder="What's the deal?"></textarea>
    <label class="flex space-x-2 items-center">
        <input type="checkbox"/>
        <span>I understand I will also submit information such as my browser and operating system to help the dev team with testing</span>
    </label>
</template>
