<template>
    <app-layout title="Courses">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Departments
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 xl:grid-cols-4 lg:grid-cols-3 sm:grid-cols-2">
                    <inertia-link v-for="department in departments.data" :key="department.id" :href="`/departments/${department.id}`">
                        <div
                             class="shadow overflow-hidden sm:rounded-md">
                            <div class="px-4 py-5 bg-white sm:p-6">
                                <h1 class="font-semibold">{{ department.name }}</h1>
                                <small>{{ department.prefix }}</small>
                                <p>{{ department.courses_count }} Course(s)</p>
                            </div>
                        </div>
                    </inertia-link>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    name: "Departments",
    components: {
        AppLayout
    },
    props: {
        departments: Object,
    },
}
</script>

<style scoped>

</style>
