<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FormButton from "@/Components/FormButton.vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    events: Object
});

const newEvent = useForm({
    name: '',
    description: '',
    location: '',

    date: null,

    info_url: '',
    purchase_url: '',
});

const submitEvent = () => {
    newEvent.post('/admin/events/new', {
        onSuccess: () => {
            newEvent.reset();
        }
    })
}
</script>
<template>
    <AdminLayout>
        <details class="pane">
            <summary>Create Event</summary>
            <div class="flex flex-col space-y-2">
                <input v-model="newEvent.name" type="text" placeholder="Event Name"/>
                <input v-model="newEvent.description" type="text" placeholder="Description"/>
                <input v-model="newEvent.location" type="text" placeholder="Location"/>

                <input v-model="newEvent.date" type="datetime-local" placeholder="owner"/>

                <input v-model="newEvent.info_url" type="text" placeholder="Info URL"/>
                <input v-model="newEvent.purchase_url" type="text" placeholder="Purchase URL"/>

                <!--files-->

                <FormButton :callback="submitEvent" :form="newEvent">Create Event</FormButton>
            </div>
        </details>
        <div class="pane">
            <table class="w-full table-auto border-neutral-300">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Links</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="event in events">
                    <td class="digit">{{ event.id }}</td>
                    <td>{{ event.name }}</td>
                    <td>{{ event.description }}</td>
                    <td>{{ event.location }}</td>
                    <td>{{ event.date ? new Date(event.date).toLocaleString('en-US', {month: 'short', day: '2-digit', weekday: 'long',hour: '2-digit', minute: '2-digit'}) : 'TBD' }}</td>
                    <td>{{ event.purchase_url }} {{ event.info_url }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
