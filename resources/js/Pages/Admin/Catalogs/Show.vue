<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.catalogs.index')"
                          class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                </svg>
                Back to Results
            </inertia-link>
        </div>

        <div class="text-center">
            <h1 class="text-2xl uppercase font-black mb-2">
                {{ catalog.name_full }}
            </h1>
        </div>

        <div class="mt-4 max-w-sm mx-auto p-4 bg-white rounded-md shadow">
            <table>
                <tbody>
                    <tr>
                        <td class="p-2 font-bold">ID</td>
                        <td class="p-2">
                            {{ catalog.id }}
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 font-bold">Active</td>
                        <td class="p-2">
                            <span :class="catalog.is_active ? 'bg-green-300' : 'bg-red-300'" class="px-2 rounded-3xl">{{ catalog.is_active ? 'Yes' : 'No' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-2 font-bold">Name</td>
                        <td class="p-2">{{ catalog.name ? catalog.name : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-bold">Semester</td>
                        <td class="p-2">{{ catalog.semester }}</td>
                    </tr>
                    <tr>
                        <td class="p-2 font-bold">Year</td>
                        <td class="p-2">{{ catalog.year }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-2 text-center">
                <inertia-link :href="route('admin.catalogs.edit', catalog)">
                    <jet-button type="button">
                        Edit Catalog
                    </jet-button>
                </inertia-link>
                <inertia-link :href="route('admin.catalogs.destroy', catalog)" as="button"
                              class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150"
                              method="delete" @click="confirmDelete">
                    Delete Catalog
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
        catalog: Object,
    },
    methods: {
        confirmDelete(e) {
            if (!window.confirm('Are you want to delete this catalog? All courses associated will be deleted also.')) {
                e.preventDefault();
            }
        }
    }
}
</script>

<style scoped>

</style>
