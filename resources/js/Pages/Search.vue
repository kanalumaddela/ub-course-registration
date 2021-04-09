<template>
    <site-layout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-4 gap-4">
                <div class="col-span-full lg:col-span-1">
                    <div class="p-4 sm:rounded bg-white">
                        <h1 class="font-bold text-xl mb-3">Filter Results</h1>
                        <collapsible header-classes="py-1 px-3 bg-gray-200" content-classes="" :start-open="true">
                            <template #header>
                                <h1>Department(s)</h1>
                            </template>

                            <div class="grid gap-y-1 max-h-40 overflow-auto">
                                <label v-for="dep in departmentsCoursesCount" class="flex items-center space-x-2 p-1" :key="`search-department-${dep.id}`">
                                    <input v-model="filterCheckboxes.departments" type="checkbox" :value="dep.id" class="form-tick appearance-none h-6 w-6 border border-gray-300 rounded-md checked:bg-blue-600 checked:border-transparent focus:outline-none">
                                    <span class="text-gray-900 font-medium">{{ dep.name + ` (${dep.section_count})` }}</span>
                                </label>
                            </div>
                        </collapsible>
                        <div class="my-3"></div>
                        <div class="text-right">
                            <inertia-link :href="searchFilterUri" preserve-state>
                                <jet-button type="button">
                                    Apply
                                </jet-button>
                            </inertia-link>
                        </div>
                    </div>
                </div>
                <div class="col-span-full lg:col-span-3">
                    <pagination class="shadow" :paginator="courses"></pagination>
                    <div class="grid gap-y-2">
                        <card classes="relative p-4 sm:rounded bg-white" v-for="course in courses.data" :data="course" :key="'course-' + course.id">
                            <span v-if="studentRegistrationsMappings.courses[course.id]" class="absolute top-1.5 right-1.5 py-1 px-3 rounded shadow" :class="`course-${studentRegistrationsMappings.courses[course.id]}`">
                                {{ capitalize(studentRegistrationsMappings.courses[course.id]) }}
                            </span>
                            <div class="flex items-center">
                                <div class="flex-grow">
                                    <h1 class="text-xl font-bold">
                                        [{{ course.name_shorthand }}] {{ course.name }}
                                        {{ course.credits ? ` - (${course.credits + (course.credits > 1 ? ' Credits' : ' Credit')})` : '' }}
                                    </h1>
                                    <p>{{ course.department.name }}</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                <p v-if="course.description">
                                    {{ course.description }}
                                </p>
                                <p v-else>
                                    No description available...
                                </p>
                            </div>
                            <hr class="my-2"/>
                            <div>
                                <collapsible class="border border-gray-200">
                                    <template #header class="cursor-pointer">
                                        <h1>View available sections ({{ getRealSectionCount(course.sections) }})</h1>
                                    </template>

                                    <div class="mb-4 border border-gray-400" v-if="section.schedule.length"
                                         v-for="section in course.sections" :key="'section-' + section.id">
                                        <div class="flex items-center px-1 py-2">
                                            <h5 class="flex-grow underline">
                                                {{ course.name_shorthand + '-' + section.number }} -
                                                {{ section.catalog.name_full }}</h5>
                                            <action-button :action="route('register.section', section.id)"
                                                           action-method="post"
                                                           :action-data="{sectionId: section.id}"
                                                           :csrf="true"
                                                           :toggle-data="[{type: 'register'}, {type: 'unregister'}]"
                                                           :toggle-text="['Add Section to your schedule', 'Remove section from schedule']"
                                                           :toggle-class="['text-white bg-blue-600 border-blue-600 hover:text-blue-600 hover:bg-white', 'text-white bg-red-600 border-red-600 hover:text-red-600 hover:bg-white']"
                                                           :initial-state="Number(studentRegistrationsMappings.sections[section.id] !== undefined)"
                                                           @state-changed="updateRegistrationBadge(section.id, section.course_id)"
                                                           class="px-3 py-1 rounded border">
                                            </action-button>
                                        </div>

                                        <table class="min-w-full table-fixed text-center">
                                            <thead>
                                                <tr>
                                                    <th class="border-t border-b border-gray-400">Registered</th>
                                                    <th class="border border-gray-400">Instructor(s)</th>
                                                    <th class="border border-gray-400">Type</th>
                                                    <th class="border border-gray-400">Times</th>
                                                    <th class="border border-gray-400">Building</th>
                                                    <th class="border border-gray-400">Room</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="border-t border-r border-gray-400" rowspan="0">
                                                        {{ section.seats_left }} / {{ section.seats }}
                                                    </td>
                                                    <td class="border-l border-gray-400" rowspan="0">
                                                        {{
                                                            section.faculty ? section.faculty.split(',').join(', ') : 'TBA'
                                                        }}
                                                    </td>
                                                </tr>
                                                <tr v-for="schedule in section.schedule"
                                                    :key="'schedule-' + schedule.id">
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
                                                    <td class="border-t border-l border-gray-400">
                                                        {{
                                                            schedule.room ? schedule.room : (!schedule.is_online ? 'TBA' : 'N/A')
                                                        }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </collapsible>
                            </div>
                        </card>
                    </div>
                </div>
            </div>
        </div>

        <modal :show="false">
            <h1>modal content test</h1>
        </modal>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import Card from "@/components/Card";
import Pagination from "@/components/Pagination";
import Collapsible from "@/components/Collapsible";
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import ActionButton from "@/components/ActionButton";
import Modal from "@/Jetstream/Modal";

export default {
    name: "Search",
    components: {
        Modal,
        ActionButton,
        JetInput,
        JetButton,
        Collapsible,
        Pagination,
        SiteLayout,
        Card
    },
    props: {
        courses: Object,
        departmentsCoursesCount: Array,
        catalogsCoursesCount: Array,
        studentRegistrations: Array,
        filterCheckboxes: Object
    },
    data() {
        return {
            studentRegistrationsMappings: {
                courses: [],
                sections: [],
                coursesSections: {},
            }
        }
    },
    computed: {
        searchFilterUri() {
            return '?' + this.encodeUriObject(this.filterCheckboxes);
        }
    },
    methods: {
        encodeUriObject(obj) {
            let uri = '';

            for (let key in obj) {
                if (uri !== '') {
                    uri += '&';
                }

                uri += key + '=' + encodeURIComponent(obj[key]);
            }

            return uri;
        },
        getRealSectionCount(sections) {
            return sections
                .filter(s => s.schedule.length > 0)
                .length;
        },
        capitalize(str) {
            return _.capitalize(str);
        },
        updateRegistrationBadge(section_id, course_id) {
            if (this.studentRegistrationsMappings.coursesSections[course_id] === undefined) {
                this.studentRegistrationsMappings.coursesSections[course_id] = [];
            }

            let index = this.studentRegistrationsMappings.coursesSections[course_id].indexOf(section_id);
            if (index !== -1) {
                this.studentRegistrationsMappings.coursesSections[course_id].splice(index, 1);
            } else {
                this.studentRegistrationsMappings.coursesSections[course_id].push(section_id);
            }

            if (this.studentRegistrationsMappings.courses[course_id] && this.studentRegistrationsMappings.coursesSections[course_id].length === 0) {
                delete this.studentRegistrationsMappings.courses[course_id];
            } else {
                this.studentRegistrationsMappings.courses[course_id] = 'pending';
            }

            this.$forceUpdate();
        }
    },
    created() {
        this.studentRegistrations.map(item => {
            this.studentRegistrationsMappings.sections[item.course_section_id] = '';
            this.studentRegistrationsMappings.courses[item.course_id] = item.status;

            if (this.studentRegistrationsMappings.coursesSections[item.course_id] === undefined) {
                this.studentRegistrationsMappings.coursesSections[item.course_id] = [];
            }

            this.studentRegistrationsMappings.coursesSections[item.course_id].push(item.course_section_id);
        });
    }
}
</script>

<style scoped>

.course-pending {
    background-color: #FCD34D;
}

.course-approved {
    background-color: #10B981;
}

.course-denied {
    background-color: #EF4444;
}
</style>
