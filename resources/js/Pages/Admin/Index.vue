<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <div class="grid grid-cols-6 gap-y-6 gap-x-3">
                <div class="col-span-2 flex items-center justify-between p-5 bg-white shadow rounded">
                    <div>
                        <h1 class="font-bold text-3xl">
                            {{ registrations.total }}
                        </h1>
                        <h2 class="font-light text-gray-400">Total Registrations</h2>
                    </div>

                    <svg class="h-16 w-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                </div>
                <div class="col-span-2 flex items-center justify-between p-5 bg-white shadow rounded">
                    <div>
                        <h1 class="font-bold text-3xl">
                            {{ registrations.year }}
                        </h1>
                        <h2 class="font-light text-gray-400">Registrations this year</h2>
                    </div>

                    <svg class="h-16 w-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                </div>
                <div class="col-span-2 flex items-center justify-between p-5 bg-white shadow rounded">
                    <div>
                        <h1 class="font-bold text-3xl">
                            {{ registrations.average_per_department }}
                        </h1>
                        <h2 class="font-light text-gray-400">Average per department</h2>
                    </div>

                    <svg class="h-16 w-16 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                </div>
                <div class="col-span-2 p-4 bg-white rounded shadow">
                    <h1 class="uppercase font-bold my-2 flex items-center">
                        <svg class="h-6 w-6 mr-1 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                        Top registered courses this year
                    </h1>

                    <hr class="pb-2">

                    <table class="text-left">
                        <thead>
                            <tr>
                                <th class="px-3 py-1">Course</th>
                                <th class="px-3 py-1">Registrations</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(row, i) in courses.topTen">
                                <td class="px-3 py-1">{{ `${row.name} (${row.name_shorthand})` }}</td>
                                <td class="px-3 py-1 text-right">{{ row.registrations }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-span-2">
                    middle
                </div>
                <div class="col-span-2">
                    pie chart here
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";


export default {
    name: "Index",
    components: {
        AdminLayout,
    },
    props: {
        registrations: Object,
        catalogs: Object,
        courses: Object,
        sections: Object,
        advisors: Object,
        departments: Object,
    },
}
</script>

<style scoped>

</style>
