<template>
    <site-layout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-4 gap-4">
                <div class="col-span-full lg:col-span-1">
                    <div class="p-4 sm:rounded bg-white">
                        <h1 class="font-bold text-xl">Filter Results</h1>
                        <jet-button>
                            Apply
                        </jet-button>
                    </div>
                </div>
                <div class="col-span-full lg:col-span-3">
                    <pagination class="shadow" :paginator="courses"></pagination>
                    <div class="grid gap-y-2">
                        <card classes="p-4 sm:rounded bg-white" v-for="course in courses.data" :data="course"
                              :key="'course-' + course.id">
                            <div class="flex items-center">
                                <div class="flex-grow">
                                    <h1 class="text-xl font-bold">
                                        [{{ course.name_shorthand }}] {{
                                            course.name
                                        }}{{
                                            course.credits ? ` - (${course.credits + (course.credits > 1 ? ' Credits' : ' Credit')})` : ''
                                        }}
                                    </h1>
                                    <p>{{ course.department.name }}</p>
                                </div>
<!--                                <button-->
<!--                                    class="self-start px-3 py-1 rounded border border-blue-600 text-blue-600 transition-colors hover:bg-blue-600 hover:text-white">-->
<!--                                    Add Course to your schedule-->
<!--                                </button>-->
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
                                    <template #header>
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
                                                           :initial-state="Number(studentRegistrations[section.id] !== undefined)"
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

export default {
    name: "Search",
    components: {
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
        studentRegistrations: Object,
    },
    data() {
        return {
            seatDisplayed: false,
            registerSectionId: null,
        }
    },
    methods: {
        getRealSectionCount(sections) {
            return sections
                .filter(s => s.schedule.length > 0)
                .length;
        },
        registerSection(ev) {
            let target = ev.target;
            target.disabled = true;
            const action = route('register.section', target.dataset.id)

            console.log(action);

            axios.post(action).then(res => {
                console.log(res.data);

                if (res.data.success) {
                    target.innerText = 'Remove section from schedule';
                    target.dataset.registerType = 'unregister';
                    target.disabled = false;
                }
            });
            //
            // this.$inertia.post(action, {
            //     _token: this.$page.props.csrf_token
            // });
        },
        toggleRegisterButton(elem) {
            elem.innerText = 'Unregister'
        }
    }
}
</script>

<style scoped>

</style>
