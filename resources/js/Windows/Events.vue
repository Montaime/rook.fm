<script setup>
const events = [
    {
        name: 'YOI TOKI - A Future Funk/Vaporwave Party ft. FIBRE',
        location: 'Chicago, IL',
        date: 'May 10th, 2025',
        link_purchase: '',
        link_info: 'https://bottomlounge.com/13709-2/14351903/yoi-toki-a-future-funk-vaporwave-party-ft-fibre/',
        image: '',
    },
    {
        name: 'Critter Kickback',
        location: 'Atlanta, GA',
        date: 'May 11th, 2025',
        link_purchase: '',
        link_info: 'https://www.instagram.com/p/DJANJZoTrwu/',
        image: '',
    },
    {
        name: 'i2K: Electronic Music Festival',
        location: 'Richmond, VA',
        date: 'June 6th, 2025',
        link_purchase: '',
        link_info: 'https://www.etix.com/ticket/p/93792918/i2kelectronic-music-festival-richmond-ember-music-hall',
        image: '',
    },
    {
        name: 'Tokyo City Lights',
        location: 'Anaheim, CA',
        date: 'June 13th, 2025',
        link_purchase: '',
        link_info: 'https://concerts.livenation.com/tokyo-city-lights-anaheim-california-06-13-2025/event/09006289DE912E80',
        image: '',
    },
]
</script>
<template>
    <h2 class="font-bold text-4xl">Events</h2>
    <div v-for="event in events" class="flex flex-col space-y-2 p-2 bg-white/50 shadow-md rounded-md">
        <div class="flex flex-col grow leading-tight">
            <span class="font-bold text-xl">{{ event.name }}</span>
            <span>{{ event.location }}</span>
            <span>{{ event.date }}</span>
        </div>
        <div class="flex flex-col space-y-1 w-fit">
            <a target="_blank" v-if="event.link_purchase" :href="event.link_purchase" class="bg-black shadow-md rounded px-2 py-1 font-bold text-white">Tickets</a>
            <a target="_blank" v-if="event.link_info" :href="event.link_info" class="bg-black shadow-md rounded px-2 py-1 font-bold text-white">Info</a>
        </div>
    </div>
</template>
