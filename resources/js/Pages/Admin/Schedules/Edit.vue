<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.sections.show', section)"
                          class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                </svg>
                Back to Section
            </inertia-link>
        </div>

        <div class="col-span-full text-center">
            <h1 class="text-2xl uppercase font-black mb-2">
                Editing schedule for: {{ section.course.name_shorthand }}-{{ section.number }} -
                {{ section.catalog.name_full }}
            </h1>
        </div>

        <div class="mt-4 max-w-xl mx-auto p-4 bg-white rounded-md shadow">
            <form :action="route('admin.schedules.update', [section, schedule])" method="post">
                <input name="_method" type="hidden" value="PUT">
                <input :value="$page.props.csrf_token" name="_token" type="hidden">
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="p-2 font-bold">ID</td>
                            <td class="p-2">
                                {{ schedule.id }}
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">
                                Online
                            </td>
                            <td class="p-2">
                                <jet-checkbox v-model="form.is_online" name="is_online" value="1"/>
                                <input-error :message="$page.props.errors.is_online"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Type</td>
                            <td class="p-2">
                                <jet-input v-model="form.type" name="type" placeholder="Online, Lecture..."
                                           type="text"/>
                                <input-error :message="$page.props.errors.type"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Start Time</td>
                            <td class="p-2">
                                <jet-input v-model="form.start_time" name="start_time" type="time"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">End Time</td>
                            <td class="p-2">
                                <jet-input v-model="form.end_time" name="end_time" type="time"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Days</td>
                            <td class="p-2">
                                <jet-input v-model="form.days" name="days" type="hidden"/>
                                <v-select v-model="form.days" :multiple="true" :options="days"
                                          :reduce="day => day.letter"
                                          label="day"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Building</td>
                            <td class="p-2">
                                <jet-input v-model="form.building_id" name="building_id" type="hidden"/>
                                <v-select v-model="form.building_id" :options="buildings"
                                          :reduce="building => building.id" label="name">
                                    <template v-slot:option="option">
                                        {{ option.name }}
                                    </template>
                                </v-select>
                                <input-error :message="$page.props.errors.building_id"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Room</td>
                            <td class="p-2">
                                <jet-input v-model="form.room" name="room" placeholder="203A, etc."
                                           type="text"/>
                                <input-error :message="$page.props.errors.room"/>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
                    <inertia-link :href="route('admin.sections.show', section)">
                        <jet-danger-button type="button">
                            Go Back
                        </jet-danger-button>
                    </inertia-link>
                    <jet-button>
                        Save Section
                    </jet-button>
                </div>
            </form>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import JetInput from "@/Jetstream/Input";
import Input from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import JetDangerButton from "@/Jetstream/DangerButton";
import JetCheckbox from "@/Jetstream/Checkbox";
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import InputError from "@/Jetstream/InputError";

export default {
    name: "Create",
    components: {
        InputError,
        Input,
        JetInput,
        AdminLayout,
        JetButton,
        JetDangerButton,
        JetCheckbox,
        vSelect,
    },
    props: {
        section: Object,
        schedule: Object,
        buildings: Array,
    },
    data() {
        return {
            days: [
                {letter: 'M', day: 'Monday'},
                {letter: 'T', day: 'Tuesday'},
                {letter: 'W', day: 'Wednesday'},
                {letter: 'TH', day: 'Thursday'},
                {letter: 'F', day: 'Friday'},
                {letter: 'S', day: 'Saturday'},
                {letter: 'SU', day: 'Sunday'},
            ],
            form: this.$inertia.form({
                is_online: Boolean(this.schedule.is_online),
                type: this.schedule.type,
                start_time: this.schedule.start_time,
                end_time: this.schedule.end_time,
                days: this.schedule.days.split(' '),
                room: this.schedule.room,
                building_id: this.schedule.building_id,
            }),
        }
    },
}
</script>

<style scoped>

</style>
