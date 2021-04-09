<template>
    <site-layout>
        <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sm:p-5 bg-white sm:rounded shadow">
                <FullCalendar :options="calendarOptions"></FullCalendar>
            </div>
        </div>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";

import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid';

export default {
    name: "Index",
    components: {
        SiteLayout,
        FullCalendar,
    },
    props: {
        schedules: Array,
        calendarMinTime: String,
        calendarMaxTime: String,
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, timeGridPlugin],
                initialView: 'timeGridWeek',
                // views: {
                //     timeGrid: {
                //         allDaySlot: false
                //     }
                // },
                headerToolbar: {
                    left: 'dayGridMonth,timeGridWeek,timeGridDay',
                    center: 'title',
                    right: 'prev,next today'
                },
                // firstDay: 1,
                slotMinTime: this.calendarMinTime,
                slotMaxTime: this.calendarMaxTime,
                events: this.schedules
            }
        }
    }
}
</script>

<style scoped>

</style>
