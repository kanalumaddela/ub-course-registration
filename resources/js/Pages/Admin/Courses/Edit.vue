<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.courses.show', course)"
                          class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                </svg>
                Back to Course
            </inertia-link>
        </div>

        <div class="mt-4 max-w-xl mx-auto p-4 bg-white rounded-md shadow">
            <form :action="route('admin.courses.update', course)" method="post">
                <input name="_method" type="hidden" value="PUT">
                <input :value="$page.props.csrf_token" name="_token" type="hidden">
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="p-2 font-bold">Name</td>
                            <td class="p-2">
                                <jet-input v-model="form.name" name="name" placeholder="Accounting 101" type="text" />
                                <input-error :message="$page.props.errors.name" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Number</td>
                            <td class="p-2">
                                <jet-input v-model="form.number" name="number" placeholder="101, 390A, etc." type="text" />
                                <input-error :message="$page.props.errors.number" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Department</td>
                            <td class="p-2">
                                <input v-model="form.department_id" name="department_id" type="hidden">
                                <v-select v-model="form.department_id" :options="departments" :reduce="department => department.id" label="name">
                                    <template v-slot:option="option">
                                        [{{ option.prefix }}] {{ option.name }}
                                    </template>
                                </v-select>
                                <input-error :message="$page.props.errors.department_id" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Credits</td>
                            <td class="p-2">
                                <jet-input v-model="form.credits" name="credits" placeholder="3" type="number" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Description</td>
                            <td class="p-2">
                                <textarea v-model="form.description" class="w-full h-24 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" name="description" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
                    <inertia-link :href="route('admin.courses.show', course)">
                        <jet-danger-button type="button">
                            Go Back
                        </jet-danger-button>
                    </inertia-link>
                    <jet-button>
                        Save Course
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
        course: Object,
        departments: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.course.name,
                number: this.course.number,
                description: this.course.description,
                credits: this.course.credits,
                department_id: this.course.department_id,
            })
        }
    },
}
</script>

<style scoped>

</style>
