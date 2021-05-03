<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.sections.index')"
                          class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                </svg>
                Back to Results
            </inertia-link>
        </div>

        <div class="max-w-screen-2xl mx-auto grid grid-cols-4 gap-2 mt-4">
            <div class="col-span-full text-center">
                <h1 class="text-2xl uppercase font-black mb-2">
                    {{ section.course.name_shorthand }}-{{ section.number }} - {{ section.catalog.name_full }}
                </h1>
            </div>

            <div class="col-span-1">
                <div class="p-4 bg-white rounded-md shadow">
                    <table>
                        <tbody>
                            <tr>
                                <td class="p-2 font-bold">ID</td>
                                <td class="p-2">
                                    {{ section.id }}
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold">Number</td>
                                <td class="p-2">{{ section.number }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold">Seats</td>
                                <td class="p-2">{{ section.seats }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold">Start</td>
                                <td class="p-2">{{ section.start_date }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold">End</td>
                                <td class="p-2">{{ section.end_date }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold">Faculty</td>
                                <td class="p-2">{{ section.faculty }}</td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold">Course</td>
                                <td class="p-2">
                                    <inertia-link :href="route('admin.courses.show', section.course)"
                                                  class="text-purple-600 hover:underline">
                                        {{ section.course.name }}
                                    </inertia-link>
                                </td>
                            </tr>
                            <tr>
                                <td class="p-2 font-bold">Catalog</td>
                                <td class="p-2">
                                    <inertia-link :href="route('admin.catalogs.show', section.catalog)"
                                                  class="text-purple-600 hover:underline">
                                        {{ section.catalog.name_full }}
                                    </inertia-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-2 text-center">
                        <inertia-link :href="route('admin.sections.edit', section)">
                            <jet-button type="button">
                                Edit Section
                            </jet-button>
                        </inertia-link>
                        <inertia-link :href="route('admin.sections.destroy', section)" as="button"
                                      class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150"
                                      method="delete" @click="confirmDelete">
                            Delete Section
                        </inertia-link>
                    </div>
                </div>
            </div>
            <div class="col-span-3">
                <div class="p-4 bg-white rounded-md shadow">
                    <h1 class="flex items-center text-xl font-bold">
                        <svg class="h-6 w-6 text-gray-600 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                  stroke-linejoin="round" stroke-width="2"/>
                        </svg>
                        Schedule
                    </h1>

                    <hr class="mt-2 mb-4">

                    <table v-for="schedule in section.schedule" :key="`schedule-${schedule.id}`"
                           class="w-full mb-4 text-center">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 border border-gray-400">Type</th>
                                <th class="px-2 py-1 border border-gray-400">Days</th>
                                <th class="px-2 py-1 border border-gray-400">Time</th>
                                <th class="px-2 py-1 border border-gray-400">Building</th>
                                <th class="px-2 py-1 border border-gray-400">Room</th>
                                <th class="px-2 py-1 border border-gray-400"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-2 py-1 border border-gray-400">{{ schedule.type }}</td>
                                <td class="px-2 py-1 border border-gray-400">{{
                                        schedule.days ? schedule.days : 'N/A'
                                    }}
                                </td>
                                <td v-if="schedule.start_time" class="px-2 py-1 border border-gray-400">
                                    {{ friendlyTime(schedule.start_time) }} - {{ friendlyTime(schedule.end_time) }}
                                </td>
                                <td v-else class="px-2 py-1 border border-gray-400">
                                    N/A
                                </td>
                                <td class="px-2 py-1 border border-gray-400">
                                    {{ schedule.building ? schedule.building.name : 'N/A' }}
                                </td>
                                <td class="px-2 py-1 border border-gray-400">{{
                                        schedule.room ? schedule.room : 'N/A'
                                    }}
                                </td>
                                <td class="px-2 py-1 border border-gray-400">
                                    <inertia-link :href="route('admin.schedules.edit', [section, schedule])">
                                        <jet-button as="button">Edit Schedule</jet-button>
                                    </inertia-link>

                                    <inertia-link :href="route('admin.schedules.delete', [section, schedule])"
                                                  as="button"
                                                  class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-600 transition ease-in-out duration-150"
                                                  method="delete" @click="confirmDelete">
                                        Delete Schedule
                                    </inertia-link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-2 text-center">
                    <inertia-link :href="route('admin.schedules.create', section)">
                        <jet-button>
                            Add Schedule
                        </jet-button>
                    </inertia-link>
                </div>
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
        section: Object,
    },
    methods: {
        confirmDelete(e) {
            if (!window.confirm('Are you want to delete this? This cannot be undone.')) {
                e.preventDefault();
            }
        }
    }
}
</script>

<style scoped>

</style>
