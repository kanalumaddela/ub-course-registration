<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <div class="flex items-center justify-between mb-2">
                <div class="w-3/4 p-2 bg-white rounded-md shadow">
                    <form :action="route('admin.courses.index')" class="flex items-center" method="get">
                        <jet-input :value="search" class="w-full mr-2" name="search" placeholder="Enter a course to search for" type="text" />
                        <jet-button>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                        </jet-button>
                    </form>
                </div>

                <inertia-link :href="route('admin.courses.create')">
                    <jet-button>Add Course</jet-button>
                </inertia-link>
            </div>
            <div class="text-right mb-2">
            </div>
            <pagination :paginator="courses" class="shadow" />

            <div class="grid grid-cols-3 gap-3 mb-2">
                <inertia-link v-for="course in courses.data" :key="`course-${course.id}`" :href="route('admin.courses.show', course)" class="p-4 bg-white rounded-md shadow">
                    <div>
                        <h1 class="font-bold">
                            {{ course.name }}
                            {{ course.credits ? ` - (${course.credits + (course.credits > 1 ? ' Credits' : ' Credit')})` : '' }}
                        </h1>
                        <div class="text-sm text-gray-500">
                            {{ course.name_shorthand }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ course.department.name }}
                        </div>
                    </div>

                    <hr class="my-2">

                    <div class="text-sm text-gray-500">
                        <p v-if="course.description">
                            {{ course.description.substr(0, 75) }}...
                        </p>
                        <p v-else>
                            No description available...
                        </p>
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
import JetInput from "@/Jetstream/Input";

export default {
    name: "Index",
    components: {
        Pagination,
        AdminLayout,
        JetButton,
        JetInput,
    },
    props: {
        courses: Object,
        search: String,
    }
}
</script>

<style scoped>

</style>
