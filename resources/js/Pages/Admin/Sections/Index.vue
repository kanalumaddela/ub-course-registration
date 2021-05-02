<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <div class="text-right mb-2">
                <inertia-link :href="route('admin.sections.create')">
                    <jet-button>Add Section</jet-button>
                </inertia-link>
            </div>
            <pagination :paginator="sections" class="shadow" />

            <div class="grid grid-cols-2 gap-3 mb-2">
                <inertia-link v-for="section in sections.data" :key="`section-${section.id}`" :href="route('admin.sections.show', section)" class="p-4 bg-white rounded-md shadow">
                    <div>
                        <h1 class="font-bold">
                            {{ section.course.name_shorthand }}-{{ section.number }}
<!--                            {{ section.course.credits ? ` - (${section.course.credits + (section.course.credits > 1 ? ' Credits' : ' Credit')})` : '' }}-->
                        </h1>
                        <div class="text-sm text-gray-500">
                            {{ section.course.name }} - {{ section.course.department.name }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ section.catalog.name_full }}
                        </div>
                    </div>

                    <hr class="my-2">

                    <div>
                        <h1 class="font-bold">Schedule</h1>
                        <table class="min-w-full table-fixed text-center">
                            <thead>
                                <tr>
                                    <th class="border border-gray-400">Instructor(s)</th>
                                    <th class="border border-gray-400">Type</th>
                                    <th class="border border-gray-400">Times</th>
                                    <th class="border border-gray-400">Building</th>
                                    <th class="border border-gray-400">Room</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-400" rowspan="0">
                                        {{ section.faculty ? section.faculty.split(',').join(', ') : 'TBA' }}
                                    </td>
                                </tr>
                                <tr v-for="schedule in section.schedule" :key="'schedule-' + schedule.id">
                                    <td class="border border-gray-400">{{ schedule.type }}</td>

                                    <td v-if="schedule.start_time" class="border border-gray-400">
                                        {{ schedule.days }}
                                        {{
                                            friendlyTime(schedule.start_time) + ' - ' + friendlyTime(schedule.end_time)
                                        }}
                                    </td>
                                    <td v-else class="border border-gray-400">TBA</td>

                                    <td class="border border-gray-400">
                                        {{
                                            schedule.building ? schedule.building.name : (!schedule.is_online ? 'TBA' : 'N/A')
                                        }}
                                    </td>
                                    <td class="border border-gray-400">
                                        {{
                                            schedule.room ? schedule.room : (!schedule.is_online ? 'TBA' : 'N/A')
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </inertia-link>
            </div>

            <pagination :paginator="sections" class="shadow" />
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
        sections: Object,
    },
}
</script>

<style scoped>

</style>
