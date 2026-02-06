<script setup>
import {onBeforeMount, ref} from "vue";

const events = ref([]);

onBeforeMount(async () => {
    loadEvents();
})

const loadEvents = () => {
    fetch('/events').then(res => res.json()).then(data => {
        events.value = data;
    });
}
</script>
<template>
    <div class="flex justify-between items-center space-x-2">
        <h2 class="font-bold text-4xl">Events</h2>
        <span class="underline cursor-pointer" @click="loadEvents">Reload</span>
    </div>
    <p v-if="events.length === 0" class="text-center">There are no events planned right now, but check back soon!</p>
    <div v-for="event in events" class="flex flex-col space-y-2 p-2 shadow-md rounded-md event-card">
        <div class="flex flex-col grow leading-tight">
            <span class="font-bold text-xl">{{ event.name }}</span>
            <span v-if="event.location">{{ event.location }}</span>
            <span>{{ event.date ? new Date(event.date).toLocaleString('en-US', {month: 'short', day: '2-digit', weekday: 'long',hour: '2-digit', minute: '2-digit'}) : 'TBD' }}</span>
            <span v-if="event.description">{{ event.description }}</span>
        </div>
        <div class="flex space-x-1 w-fit">
            <a target="_blank" v-if="event.purchase_url" :href="event.purchase_url" class="bg-black shadow-md rounded px-2 py-1 font-bold text-white">Tickets</a>
            <a target="_blank" v-if="event.info_url" :href="event.info_url" class="bg-black shadow-md rounded px-2 py-1 font-bold text-white">Info</a>
        </div>
    </div>
</template>
