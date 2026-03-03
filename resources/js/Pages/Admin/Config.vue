<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FormButton from "@/Components/FormButton.vue";
import {useForm} from "@inertiajs/vue3";
import {onBeforeMount, ref, toRaw} from "vue";

const props = defineProps({
    config: Object
});

const updates = useForm({
    config: structuredClone(toRaw(props.config)),
});

const submit = () => {
    updates.post('/admin/config');
}

const settings = ref({});

onBeforeMount(() => {
    for (let setting in updates.config) {
        settings.value[setting] = {open: true};

        switch (typeof(updates.config[setting])) {
            case 'number':
                settings.value[setting].type = 'number';
                break;
            case 'boolean':
                settings.value[setting].type = 'boolean';
                break;
            case 'string':
                settings.value[setting].type = 'string';
                break;
            case 'object':
                settings.value[setting].type = props.config[setting] === null ? 'null' : 'object';
                break;
            default:
                settings.value[setting].type = 'unknown';
                break;
        }
    }
})
</script>
<template>
    <AdminLayout>
        <FormButton :callback="submit" :form="updates">Submit Config</FormButton>
        <div v-for="(setting, key) in updates.config" class="flex flex-col pane gap-2">
            <div class="flex items-center gap-1">
                <h2 class="rounded-md bg-black px-2 font-mono text-white w-fit">{{ key }}</h2>
                <select v-model="settings[key].type" :disabled="!settings[key].open" class="px-2 py-0.5 w-24 text-sm">
                    <option value="string">Text</option>
                    <option value="obj">Object</option>
                    <option value="number">Number</option>
                    <option value="boolean">Boolean</option>
                    <option value="null">Null</option>
                </select>
                <div @click="settings[key].open = !settings[key].open" class="flex justify-end grow">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-5" :class="{'rotate-90': !settings[key].open}">
                        <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <template v-if="settings[key].open">
                <textarea v-if="settings[key].type === 'string'" v-model="updates.config[key]" placeholder="Text"></textarea>
                <textarea v-if="settings[key].type === 'object'" placeholder="obj"></textarea>
                <input v-if="settings[key].type === 'number'" v-model.number="updates.config[key]" type="number" class="px-2 py-1" placeholder="Number"/>
                <label v-if="settings[key].type === 'boolean'" class="flex items-center space-x-1">
                    <input v-model="updates.config[key]" :checked="updates.config[key]" type="checkbox"/>
                    <span>True</span>
                </label>
                <span v-if="settings[key].type === 'null'" class="opacity-50 italic">null</span>
            </template>
        </div>
    </AdminLayout>
</template>
