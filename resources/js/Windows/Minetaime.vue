<script setup>

import {ref} from "vue";

const rows = ref(10);
const column = ref(10);
</script>
<template>
    <div>
        <div style="grid-template-rows: repeat(10, minmax(0, 1fr)); grid-template-columns: repeat(10, minmax(0, 1fr));">
            <div></div>
        </div>
    </div>
</template>
