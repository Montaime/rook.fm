<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    stats: Object
})
</script>
<template>
    <AdminLayout>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="pane">
                <h2 class="font-bold text-xl">Home</h2>
                <p>Admin panel</p>
            </div>
            <div class="pane">
                <h2 class="font-bold text-xl">Statistics</h2>
                <span v-for="(stat, name) in stats" class="flex space-x-2 justify-between capitalize">
                    <span>{{ name }}</span>
                    <span>{{ stat }}</span>
                </span>
            </div>
            <div class="pane">
                <h2 class="font-bold text-xl">Goku</h2>
                <img alt="" src="@/../assets/goku_burger.png"/>
            </div>
        </div>
    </AdminLayout>
</template>
<style scoped>

</style>
