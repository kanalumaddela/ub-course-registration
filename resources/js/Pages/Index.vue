<template>
    <site-layout>
        <div class="py-12 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <inertia-link v-if="hasRole('admin')" :href="route('admin.index')">
                <div class="p-4 mb-2 text-white bg-indigo-500 rounded-md shadow flex items-center">
                    Vist the Admin dashboard
                    <svg class="ml-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                </div>
            </inertia-link>
            <inertia-link v-if="hasRole('advisor')" :href="route('advisor.registrations')">
                <div class="p-4 mb-4 bg-yellow-500 rounded-md shadow flex items-center">
                    Vist the Advisor dashboard
                    <svg class="ml-2 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13 7l5 5m0 0l-5 5m5-5H6" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                </div>
            </inertia-link>

            <div v-if="$page.props.user && registrationList" class="grid md:grid-cols-4 bg-white sm:rounded-md shadow" style="height: 50rem;">
                <div class="col-span-1 sm:p-5 border-r sm:border-gray-300">
                    <div class="h-full overflow-auto">
                        <div v-if="!calendarOptions.events.length" class="h-full flex flex-col items-center justify-center">
                            <h1>Looks a bit looks empty...</h1>
                            <inertia-link :href="route('search')">
                                <jet-button type="button">
                                    Register
                                </jet-button>
                            </inertia-link>
                        </div>
                        <template v-else>
                            <div class="py-2 px-4">
                                <h1 class="font-bold uppercase tracking-wide">
                                    My Classes
                                </h1>
                            </div>
                            <div v-show="registrationList.length" class="mt-2 mb-4 text-center">
                                <inertia-link :href="route('studentRegistration.registerAll')" as="button" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 bg-purple-600 hover:bg-purple-500" method="post">
                                    Register for all
                                </inertia-link>
                            </div>
                            <div class="pt-4 pb-2 px-2 overflow-auto" style="max-height: 40.5rem">
                                <div v-for="registration in registrationList" :key="`student-registration-${registration.user_id}-${registration.course_section_id}`" class="relative pt-4 pb-2 px-4 border border-gray-300 rounded mb-4">
                                    <inertia-link :href="route('courses.view', registration.course_section.course)" class="underline">
                                        <h1>{{ registration.course_section.course.name + `(${registration.course_section.course.name_shorthand + '-' + registration.course_section.number})` }}</h1>
                                    </inertia-link>
                                    <span class="text-xs italic text-gray-400">
                                        {{ registration.course_section.catalog.name_full }}
                                    </span>
                                    <p :class="`course-${registration.status}`" class="absolute -top-3 -right-2 py-0.5 px-2 rounded">{{ determineBadge(registration.status) }}</p>
                                    <div class="mt-2 text-center flex justify-center">
                                        <jet-button v-if="registration.status === 'planned' || registration.status === 'approved'" class="rounded-r-none" type="button" @click.native="confirmModal($event, registration, 'register')">Register</jet-button>
                                        <jet-button v-if="registration.status === 'pending'" :class="{'rounded-r-none': registration.status !== 'approved'}" type="button" @click.native="confirmModal($event, registration, 'cancel')">Cancel</jet-button>
                                        <jet-danger-button v-if="registration.status === 'registered'" @click.native="confirmModal($event, registration, 'drop')">Drop</jet-danger-button>
                                        <jet-danger-button v-if="registration.status !== 'registered'" :class="{'rounded-l-none': registration.status !== 'registered' && registration.status !== 'denied'}" @click.native="confirmModal($event, registration, 'remove')">Remove</jet-danger-button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                <div class="col-span-3 sm:p-5" style="height: 49rem">
                    <FullCalendar :options="calendarOptions" class="h-full"></FullCalendar>
                </div>
            </div>

            <div v-else class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <site-welcome :courses-count="coursesCount" />
            </div>
        </div>

        <confirmation-modal :show="isPerformingRegistrationAction" @close="closeConfirmModal">
            <template #title>
                {{ modalData.title }}
            </template>

            <template #content>
                <span v-html="modalData.message"></span>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="closeConfirmModal">
                    Nevermind
                </jet-secondary-button>

                <jet-danger-button @click.native="submitActionForm">
                    {{ modalData.dangerText }}
                </jet-danger-button>
            </template>
        </confirmation-modal>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import JetButton from "@/Jetstream/Button";
import JetSecondaryButton from '@/Jetstream/SecondaryButton';
import JetDangerButton from '@/Jetstream/DangerButton';
import SiteWelcome from "@/components/SiteWelcome";
import ConfirmationModal from "@/Jetstream/ConfirmationModal";

import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid';


const questionTemplate = (action, courseName) => `Are you sure you want to ${action} <span class="font-bold inline-block">${courseName}</span>?`;

const confirmationData = {
    register: {
        confirmation: false,
    },
    cancel: {
        confirmation: true,
        title: 'Cancel registration',
        buttonText: 'Cancel registration',
        message: (action, courseName) => `Are you sure you want to cancel registering for <span class="font-bold inline-block">${courseName}</span>?`,
    },
    drop: {
        confirmation: true,
        formMethod: 'delete',
        title: 'Drop course',
        buttonText: 'Drop Course',
        message: (action, courseName) => questionTemplate(action, courseName) + ' You will need advisor approval again if you re-register.',
    },
    remove: {
        confirmation: true,
        formMethod: 'delete',
        title: 'Remove from schedule',
        buttonText: 'Remove',
    }
}

export default {
    name: "Index",
    components: {
        ConfirmationModal,
        SiteWelcome,
        SiteLayout,
        JetButton,
        JetSecondaryButton,
        JetDangerButton,
        FullCalendar,
    },
    props: {
        schedules: {
            type: Array,
            default: () => []
        },
        coursesCount: {
            type: Number,
            default: 0
        },
        registrationList: Array,
        calendarMinTime: String,
        calendarMaxTime: String,
        hasOnlineClasses: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, timeGridPlugin],
                initialView: 'timeGridWeek',
                views: {
                    timeGrid: {
                        allDaySlot: this.hasOnlineClasses
                    }
                },
                headerToolbar: {
                    left: 'dayGridMonth,timeGridWeek,timeGridDay',
                    center: 'title',
                    right: 'prev,next today'
                },
                // firstDay: 1,
                slotMinTime: this.calendarMinTime,
                slotMaxTime: this.calendarMaxTime,
                events: this.schedules
            },
            registrationActionForm: this.$inertia.form({
                action: null,
            }),
            studentRegistration: null,
            isPerformingRegistrationAction: false,
            modalData: {
                title: null,
                message: null,
                dangerText: null,
            }
        }
    },
    methods: {
        determineBadge(status) {
            switch (status) {
                case 'pending':
                    return 'pending approval';
                default:
                    return status;
            }
        },
        confirmModal(ev, registration, type) {
            this.studentRegistration = registration;
            this.registrationActionForm.action = type;

            if (!confirmationData[type]?.confirmation) {
                this.submitActionForm();
                return;
            }

            this.modalData.title = confirmationData[type].title;

            const courseName = `${registration.course_section.course.name} (${registration.course_section.course.name_shorthand}-${registration.course_section.number})`;
            this.modalData.message = confirmationData[type].message ? confirmationData[type].message(type, courseName) : questionTemplate(type, courseName);

            this.modalData.dangerText = confirmationData[type].buttonText ? confirmationData[type].buttonText : type;

            this.isPerformingRegistrationAction = true;
        },
        closeConfirmModal() {
            this.isPerformingRegistrationAction = false;
            this.resetActionForm();
        },
        submitActionForm() {
            const method = confirmationData[this.registrationActionForm.action].formMethod ? confirmationData[this.registrationActionForm.action].formMethod : 'update';
            const formMethod = method !== 'delete' ? 'post' : 'delete';

            this.registrationActionForm[formMethod](route('studentRegistration.' + method, this.studentRegistration.id), {
                onSuccess: () => {
                    this.closeConfirmModal();
                }
            })
        },
        resetActionForm() {
            this.studentRegistration = null;
            this.registrationActionForm.reset();
        },
        updateCalendarOptions() {
            this.calendarOptions.views.timeGrid.allDaySlot = this.hasOnlineClasses;
            this.calendarOptions.slotMinTime = this.calendarMinTime;
            this.calendarOptions.slotMaxTime = this.calendarMaxTime;
            this.calendarOptions.events = this.schedules;
        }
    },
    beforeUpdate() {
        this.updateCalendarOptions();
    },
}
</script>

<style>
.fc-v-event {
    border-color: #b5b5b5;
}
.fc-event-main-frame {
    padding: .25rem;
}
</style>
