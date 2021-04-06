<template>
    <app-layout title="Courses">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Courses
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <pagination :paginator="courses" />

                <div class="py-2 grid grid-cols-1 gap-4 lg:grid-cols-3 sm:grid-cols-2">
                    <inertia-link v-for="course in courses.data" :href="`/courses/${course.id}`" :key="`course-${course.id}`">
                        <div class="shadow overflow-hidden sm:rounded-md bg-white" style="height: 100%">
                            <div class="px-6 py-4" style="border-bottom: 1px solid rgba(0,0,0,.1)">
                                <h1 class="font-bold">{{ course.name }}</h1>
                                <small class="text-gray-500">{{ course.name_shorthand }}</small>
                            </div>
                            <div class="p-6">
                                <p class="" v-if="course.description">{{ course.description | truncate(75) }}</p>
                                <p class="" v-else>No description found...</p>
                            </div>
                        </div>
                    </inertia-link>
                </div>

                <pagination :paginator="courses" />
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Pagination from "@/components/Pagination";

export default {
    name: "Courses",
    components: {
        Pagination,
        AppLayout
    },
    props: {
        courses: Object,
    },
    filters: {
        truncate: function (value, length = 50, clamp = '...') {
            // clamp = clamp || '...';
            const node = document.createElement('div');
            node.innerHTML = value;
            const content = node.textContent;

            return content.length > length ? content.slice(0, length) + clamp : content;
        }
    }
}
</script>

<style scoped>

</style>
