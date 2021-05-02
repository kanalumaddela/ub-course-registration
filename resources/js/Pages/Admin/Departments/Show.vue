<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.departments.index')" class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                Back to Results
            </inertia-link>
        </div>
        <div class="mt-4 max-w-sm mx-auto p-4 bg-white rounded-md shadow">
            <table>
                <tbody>
                    <tr>
                        <td class="p-2 font-bold">ID</td>
                        <td class="p-2">
                            {{ department.id }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 font-bold">Name</td>
                        <td class="p-2">{{ department.name }}</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-bold">Prefix</td>
                        <td class="p-2">{{ department.prefix }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-2 text-center">
                <inertia-link :href="route('admin.departments.edit', department)">
                    <jet-button type="button">
                        Edit Department
                    </jet-button>
                </inertia-link>
                <inertia-link :href="route('admin.departments.destroy', department)" as="button" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150" method="delete" @click="test">
                    Delete Department
                </inertia-link>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import JetButton from '@/Jetstream/Button';
import JetDangerButton from '@/Jetstream/DangerButton';

export default {
    name: "Show",
    components: {
        AdminLayout,
        JetButton,
        JetDangerButton,
    },
    props: {
        department: Object,
    },
    methods: {
        test(e) {
            if (!window.confirm('Are you want to delete this department? All courses associated will be deleted also.')) {
                e.preventDefault();
            }
        }
    }
}
</script>

<style scoped>

</style>
