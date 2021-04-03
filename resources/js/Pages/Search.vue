<template>
    <site-layout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-4 gap-4">
                <div class="col-span-full lg:col-span-1">
                    <div class="p-4 sm:rounded bg-white">
                        <h1 class="font-bold text-xl">Filter Results</h1>
                    </div>
                </div>
                <div class="col-span-full lg:col-span-3">
                    <pagination class="shadow" :paginator="courses"></pagination>
                    <div class="grid gap-y-2">
                        <card classes="p-4 sm:rounded bg-white" v-for="course in courses.data" :data="course" :key="'course-' + course.id">
                            <h1 class="text-xl font-bold">[{{ course.name_shorthand }}] {{ course.name }}{{ course.credits ? ` - (${course.credits + (course.credits > 1 ? ' Credits' : ' Credit')})` : '' }}</h1>
                            <div class="text-sm text-gray-500">
                                <p v-if="course.description">
                                    {{ course.description }}
                                </p>
                                <p v-else>
                                    No description available...
                                </p>
                            </div>
                            <hr class="my-1"/>
                            <div>
                                <h3 @click="toggleSections" class="text-lg font-medium mb-2">Sections</h3>
                                <div>
                                    <div class="mb-2" v-if="section.schedule.length" v-for="section in course.sections" :key="'section-' + section.id">
                                        <h5>{{ course.name_shorthand + '-' + section.number }} - {{ section.catalog.name_full }}</h5>
                                        <table class="min-w-full table-auto border border-gray-400 text-center">
                                            <thead>
                                                <tr>
                                                    <th class="border border-gray-400">Seats</th>
                                                    <th class="border border-gray-400">Days</th>
                                                    <th class="border border-gray-400">Times</th>
                                                    <th class="border border-gray-400">Location</th>
                                                    <th class="border border-gray-400">Instructor(s)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="border border-gray-400" :rowspan="0">{{ section.seats }}</td>
                                                </tr>
                                                <tr v-for="schedule in section.schedule" :key="'schedule-' + schedule.id">
                                                    <td class="border border-gray-400">{{ schedule.days.join(' ') }}</td>
                                                    <td class="border border-gray-400">{{ friendlyTime(schedule.start_time) + ' - ' + friendlyTime(schedule.end_time) }}</td>
                                                    <td class="border border-gray-400">{{ schedule.location_id }}</td>
                                                    <td class="border border-gray-400">Teacher</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </card>
                    </div>
                </div>
            </div>
        </div>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import Card from "@/components/Card";
import Pagination from "@/components/Pagination";

export default {
    name: "Search",
    components: {
        Pagination,
        SiteLayout,
        Card
    },
    props: {
        courses: Object,
        departmentsCoursesCount: Array,
        catalogsCoursesCount: Array,
    },
    methods: {
        toggleSections(ev) {
            return;

            let elem = ev.target;
            console.log(elem);
            elem.nextSibling.nextSibling.classList.toggle('hidden');
        }
    },
    data() {
        return {
            seatDisplayed: false
        }
    }
}
</script>

<style scoped>

</style>
