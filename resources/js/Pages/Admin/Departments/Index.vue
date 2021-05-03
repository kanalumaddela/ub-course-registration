<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <div class="text-right mb-2">
                <inertia-link :href="route('admin.departments.create')">
                    <jet-button>Add Department</jet-button>
                </inertia-link>
            </div>
            <pagination :paginator="departments" class="shadow" />

            <div class="grid grid-cols-4 gap-4 mb-2">
                <inertia-link v-for="department in departments.data" :key="`department-${department.id}`" :href="route('admin.departments.show', department)" class="col-span-1 p-4 bg-white rounded-md shadow">
                    <div>
                        {{ department.name }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ department.prefix }}
                    </div>
                </inertia-link>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import JetButton from '@/Jetstream/Button';
import Pagination from "@/components/Pagination";

export default {
    name: "Index",
    components: {
        Pagination,
        AdminLayout,
        JetButton,
    },
    props: {
        departments: Object,
    }
}
</script>

<style scoped>

</style>
