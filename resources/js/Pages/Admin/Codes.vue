<script setup>
import {useForm, Link} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import FormButton from "@/Components/FormButton.vue";

const props = defineProps({
    codes: Object,
    result: Object,
});

const batchForm = useForm({
    prefix: '',
    count: 0,
    club_id: null,
    codes: null,
});

const file = ref('');
const failed = ref(false);

watch(file, () => {
    let json;

    try {
        json = JSON.parse(file.value);
    } catch (e) {
        failed.value = true;
        return;
    }

    failed.value = false;

    batchForm.count = json.codes.length;
    batchForm.club_id = json.club_id;
    batchForm.codes = json.codes;

    if (json.codes.length > 0) batchForm.prefix = json.codes[0].split('-')[0];
});

const generate = () => {
    batchForm.post('/admin/codes', {
        preserveScroll: true,
        onSuccess: () => {
            batchForm.reset();
        }
    })
}


const exportData = ref('Data will appear here once you select a format');
const exportFormat = ref('none');
const exportMIME = ref('');
watch(exportFormat, () => {
    let res = props.result;

    if (exportFormat.value === 'JSON') {
        exportData.value = JSON.stringify(res, null, 2);
        exportMIME.value = 'application/json';
    } else if (exportFormat.value === 'CSV') {
        exportData.value = 'Code,Club ID,Creator ID,Timestamp\n' + res.codes.map(_ => [_, res.club_id, res.creator_id, res.timestamp].join(',')).join('\n');
        exportMIME.value = 'text/csv';
    } else if (exportFormat.value === 'TXT') {
        exportData.value = res.codes.join('\n');
        exportMIME.value = 'text/plain';
    }
});

const downloadURL = (text, type) => URL.createObjectURL(new Blob([text], {type: type}));
</script>
<template>
    <AdminLayout>
        <details class="pane">
            <summary>Generate Codes</summary>
            <div class="flex flex-col p-2">
                <span class="text-sm mt-2">Prefix</span>
                <input v-model="batchForm.prefix" type="text" placeholder="Prefix" :disabled="file.length > 0"/>

                <span class="text-sm mt-2">Count</span>
                <input v-model="batchForm.count" type="number" placeholder="Count" :disabled="file.length > 0"/>

                <span class="text-sm mt-2">Club ID</span>
                <input v-model="batchForm.club_id" type="number" placeholder="Club ID" :disabled="file.length > 0"/>

                <span class="text-sm mt-2">Import Codes</span>
                <textarea v-model="file" class="bg-zinc-200 rounded-md border-none font-mono text-sm"></textarea>
                <div class="flex space-x-2 items-center text-sm mt-1" :class="{'invisible': file.length === 0}">
                    <button @click="file = ''" class="text-red-500 rounded-md bg-zinc-100 border border-zinc-300 px-2 py-1 w-fit">Remove Imported Codes</button>
                    <span class="text-red-500" v-show="failed">Failed to parse</span>
                </div>

                <div class="flex flex-col rounded-md bg-zinc-100 px-2 py-1 mt-2">
                    <span class="text-sm">Template</span>
                    <span>{{ batchForm.prefix.toUpperCase() }}-XXXX-XXXX-XXXX</span>
                </div>

                <FormButton class="mt-1" :callback="generate" :form="batchForm">{{ file.length > 0 ? 'Import/' : '' }}Generate Codes</FormButton>
            </div>
        </details>
        <details v-if="result" open>
            <summary>Results</summary>
            <div class="flex flex-col p-4 border border-ui-300 rounded-md">
                <span class="text-sm mt-2">Export Format</span>
                <select v-model="exportFormat" class="bg-zinc-200 rounded-md border-none">\
                    <option disabled selected value="none">Select a Format</option>
                    <option>JSON</option>
                    <option>CSV</option>
                    <option>TXT</option>
                </select>

                <span class="text-sm mt-2">Data</span>
                <textarea v-model="exportData" class="bg-zinc-200 rounded-md border-none font-mono text-sm min-h-64"></textarea>
                <a v-if="exportFormat !== 'none'" :href="downloadURL(exportData, exportMIME)" :download="`${result.prefix}_export.${exportFormat.toLowerCase()}`" class="text-sm rounded-md bg-zinc-100 border border-zinc-300 px-2 py-1 w-fit mt-1">Download File</a>
            </div>
        </details>
        <div class="flex items-center space-x-1 w-fit p-1">
            <Link :href="link.url ?? ''" v-for="(link, i) in codes.links" class="px-2 bg-zinc-200 hover:bg-zinc-400 rounded-md">{{ i === 0 ? 'Prev' : (i === codes.links.length - 1 ? 'Next' : link.label) }}</Link>
        </div>
        <div class="pane">
            <table class="table-auto">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Club</th>
                    <th>Creator</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="code in codes.data">
                    <td class="digit">{{ code.id }}</td>
                    <td class="text-sm font-mono">{{ code.code.split('-')[0] }}-XXXX-XXXX-XXXX</td>
                    <td>{{ code.club_id }}</td>
                    <td>{{ code.creator_id }}</td>
                    <td>{{ new Date(code.created_at).toLocaleString() }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
