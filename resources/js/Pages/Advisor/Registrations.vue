<template>
    <site-layout>
        <div class="py-12 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <div v-if="!students.data.length" class="flex items-center bg-white rounded-md shadow" style="height: 46rem">
                <div class="flex-grow text-center text-gray-400">
                    <svg class="inline-block h-52 w-52" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        <path d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                    </svg>
                    <h1 class="text-2xl">No pending registrations</h1>
                </div>
            </div>
            <template v-else>
                <pagination :paginator="students" />

                <div class="grid grid-cols-5 bg-white rounded-md shadow">
                    <div class="col-span-1 border-r border-gray-300">
                        <h1 class="p-3 text-xl font-bold uppercase tracking-wide">Students</h1>
                        <div class="max-h-full overflow-auto" style="height: calc(44rem - 52px)">
                            <div v-for="student in students.data" :key="`student-${student.id}`" :class="{'bg-gray-200' : activeStudent.id === student.id}" class="flex cursor-pointer items-center w-full p-3 border-t border-gray-300 hover:bg-gray-200" @click="activeStudent = student">
                                <img v-if="$page.props.jetstream.managesProfilePhotos" :alt="student.name" :src="student.profile_photo_url" class="mr-2 h-10 w-10 rounded-full object-cover" />
                                <div>
                                    <h1 class="text-lg">{{ student.name }}</h1>
                                    <h1 class="-mt-1 text-sm text-gray-400">{{ student.email }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-4">
                        <div v-if="activeStudent">
                            <h1 class="p-3 text-xl font-bold uppercase tracking-wide">Student Info</h1>
                            <div class="border-t border-gray-300">
                                <div class="flex items-center p-5 justify-between">
                                    <div class="flex items-center">
                                        <img v-if="$page.props.jetstream.managesProfilePhotos" :alt="activeStudent.name" :src="activeStudent.profile_photo_url_large" class="mr-2 h-24 w-24 rounded-full object-cover" />
                                        <div>
                                            <h1 class="font-medium text-xl">{{ activeStudent.name }}</h1>
                                            <h1 class="text-gray-400">{{ activeStudent.email }}</h1>
                                        </div>
                                    </div>
                                    <div class="grid gap-1.5">
                                        <a :href="route('users.view', activeStudent)" target="_blank">
                                            <jet-button class="justify-center">
                                                View Profile
                                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                </svg>
                                            </jet-button>
                                        </a>
                                        <a :href="route('messages.index', { to: activeStudent.id })" target="_blank">
                                            <jet-button class="w-full justify-center">
                                                Message
                                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                </svg>
                                            </jet-button>
                                        </a>
                                        <jet-button class="justify-center" @click.native="getStudentSchedule(activeStudent.id)">
                                            View Schedule
                                        </jet-button>
                                    </div>
                                </div>
                                <div class="p-5 border-t border-gray-300">
                                    <h1 class="text-xl mb-3.5">Pending Registrations</h1>
                                    <div class="grid grid-cols-2 gap-2 overflow-auto" style="max-height: 27rem">
                                        <div v-for="registration in activeStudent.registrations" :key="`registration-${registration.id}`" class="flex items-center justify-between border border-gray-300 p-3 rounded-md">
                                            <div>
                                                <h1 class="font-bold">[{{ registration.course_section.course.name_shorthand + '-' + registration.course_section.number }}] {{ registration.course_section.course.name }}</h1>
                                                <h2 class="text-sm text-gray-400">{{ registration.course_section.course.department.name }}</h2>
                                                <h2 class="text-sm text-gray-400">{{ registration.course_section.catalog.name_full }}</h2>
                                                <span :class="`course-${registration.status}`" class="px-2 py-0.5 rounded">
                                                    {{ registration.status }}
                                                </span>
                                                <p>{{ luxonFormatFriendly(registration.created_at) }}</p>
                                            </div>
                                            <div class="grid gap-y-1">
                                                <jet-button class="bg-green-600 hover:bg-green-500" @click.native="confirmRegistrationAction(registration, 'approve')">Approve</jet-button>
                                                <jet-danger-button @click.native="confirmRegistrationAction(registration, 'deny')">Deny</jet-danger-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <dialog-modal :show="showCalendar" max-width="screen-xl" @close="showCalendar = false">
            <template #title>
                {{ activeStudent.name }}'s Schedule
            </template>
            <template #content>
                <div style="height: 44rem;">
                    <FullCalendar ref="fullCalendar" :options="calendarOptions" class="h-full" />
                </div>
            </template>
        </dialog-modal>

        <confirmation-modal :show="isPerformingRegistrationAction" @close="closeConfirmModal">
            <template #title>
                {{ capitalize(registrationActionForm.action) }} Registration
            </template>

            <template #content>
                <template v-if="registrationActionModal">
                    Are you sure you want to approve the following registration for <b>{{ activeStudent.name }}</b>?
                    <table class="mt-1 w-full">
                        <thead>
                            <tr>
                                <th class="border border-gray-400 py-2 px-4">Course</th>
                                <th class="border border-gray-400 py-2 px-4">Semester</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-400 py-2 px-4">{{ registrationActionModal.course_section.course.name }} ({{ registrationActionModal.course_section.course.name_shorthand + '-' + registrationActionModal.course_section.number }})</td>
                                <td class="border border-gray-400 py-2 px-4">{{ registrationActionModal.course_section.catalog.name_full }}</td>
                            </tr>
                        </tbody>
                    </table>
                </template>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="closeConfirmModal">
                    Cancel
                </jet-secondary-button>

                <jet-danger-button :class="{ 'opacity-25': registrationActionForm.processing }" :disabled="registrationActionForm.processing" @click.native="submitActionForm">
                    {{ registrationActionForm.action }}
                </jet-danger-button>
            </template>
        </confirmation-modal>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import Pagination from "@/components/Pagination";
import JetButton from '@/Jetstream/Button';
import JetDangerButton from '@/Jetstream/DangerButton';
import JetSecondaryButton from '@/Jetstream/SecondaryButton';
import ConfirmationModal from "@/Jetstream/ConfirmationModal";
import FullCalendar from '@fullcalendar/vue';
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import DialogModal from "@/Jetstream/DialogModal";
import {DateTime} from "luxon";
import {Inertia} from '@inertiajs/inertia';

const dayMappings = {
    'SU': 0,
    'M' : 1,
    'T' : 2,
    'W' : 3,
    'TH': 4,
    'F' : 5,
    'S' : 6,
};

const defaultCalendarOptions = {
    plugins: [dayGridPlugin, timeGridPlugin],
    initialView: 'timeGridWeek',
    views: {
        timeGrid: {
            allDaySlot: false
        }
    },
    headerToolbar: {
        left: 'dayGridMonth,timeGridWeek,timeGridDay',
        center: 'title',
        right: 'prev,next today'
    },
}

export default {
    name: "Registrations",
    components: {
        DialogModal,
        ConfirmationModal,
        Pagination,
        SiteLayout,
        JetButton,
        JetDangerButton,
        JetSecondaryButton,
        FullCalendar,
    },
    props: {
        students: {
            type: Object,
            default: () => {
                return {
                    data: []
                }
            }
        },
        student: {
            type: Object,
            default: null,
        },
        forceActiveStudent: Object,
    },
    data() {
        return {
            showCalendar: false,
            calendarOptions: defaultCalendarOptions,
            calendarOptionsCache: {},
            isPerformingRegistrationAction: false,
            registrationActionForm: this.$inertia.form({
                action: null,
            }),
            registrationActionModal: null,
            activeStudent: {
                id: 0,
            },
            fullCalendarInstance: null,
        }
    },
    mounted() {
        if (this.students.data.length > 0) {
            this.activeStudent = this.students.data[0];
        }
        if (this.forceActiveStudent) {
            this.activeStudent = this.forceActiveStudent;
        }

        Inertia.on('success', () => {
            this.activeStudent = this.forceActiveStudent;
        });
    },
    methods: {
        luxonFormatFriendly(timestamp) {
            return DateTime.fromISO(timestamp).toRelative();
        },
        prepCalendar(options) {
            let event = options.events[0];
            if (event.extendedProps?.start_date) {
                this.fullCalendarInstance.gotoDate(event.extendedProps.start_date);
                setTimeout(() => {
                    this.fullCalendarInstance.render();
                }, 10);
            }
        },
        getStudentSchedule(student_id) {
            if (this.$refs.fullCalendar !== undefined && !this.fullCalendarInstance) {
                this.fullCalendarInstance = this.$refs.fullCalendar.getApi();
            }

            if (student_id === this.activeStudent.id && this.calendarOptionsCache['student-' + student_id]) {
                this.calendarOptions = this.calendarOptionsCache['student-' + student_id];
                this.prepCalendar(this.calendarOptions);
                this.showCalendar = true;
                return;
            }

            axios.get(route('advisor.students.schedule', student_id)).then(res => {
                let data = res.data;

                let options = {
                    ...defaultCalendarOptions,
                    ...{
                        views: {
                            timeGrid: {
                                allDaySlot: data.hasOnline,
                            }
                        },
                        events: data.events,
                    }
                }

                if (data.dates) {
                    options.events[0].extendedProps = data.dates;
                }

                if (data.minTime) {
                    options.slotMinTime = data.minTime;
                    options.slotMaxTime = data.maxTime;
                }

                this.calendarOptions = options;
                this.prepCalendar(this.calendarOptions);

                this.calendarOptionsCache['student-' + student_id] = this.calendarOptions;
                this.showCalendar = true;
            });
        },
        confirmRegistrationAction(registration, action = 'deny') {
            this.registrationActionModal = registration;
            this.registrationActionForm.action = action;
            this.isPerformingRegistrationAction = true;
        },
        capitalize(str) {
            return _.capitalize(str);
        },
        closeConfirmModal() {
            this.isPerformingRegistrationAction = false;
            setTimeout(() => {
                this.registrationActionForm.reset();
            }, 500);
        },
        submitActionForm() {
            this.registrationActionForm.post(route('advisor.registrations.update', this.registrationActionModal), {
                onSuccess: () => {
                    this.closeConfirmModal();
                },
            });
        }
    },
}
</script>

<style scoped>

</style>
