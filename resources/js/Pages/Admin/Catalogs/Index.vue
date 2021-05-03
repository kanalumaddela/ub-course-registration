<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <div class="text-right mb-2">
                <inertia-link :href="route('admin.catalogs.create')">
                    <jet-button>Add Catalog</jet-button>
                </inertia-link>
            </div>
            <pagination :paginator="catalogs" class="shadow" />

            <div class="grid grid-cols-4 gap-4 mb-2">
                <inertia-link v-for="catalog in catalogs.data" :key="`catalog-${catalog.id}`" :href="route('admin.catalogs.show', catalog)" class="col-span-1 p-4 bg-white rounded-md shadow">
                    <div class="flex items-center justify-between">
                        {{ catalog.name_full }}
                        <span :class="catalog.is_active ? 'bg-green-600' : 'bg-red-600'"
                              class="px-2 py-1 text-sm rounded-3xl text-white">{{
                                catalog.is_active ? 'Active' : 'Inactive'
                            }}</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ catalog.semester }}
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
        catalogs: Object,
    }
}
</script>

<style scoped>

</style>
