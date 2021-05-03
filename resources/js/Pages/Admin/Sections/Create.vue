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

        <div class="col-span-full text-center">
            <h1 class="text-2xl uppercase font-black mb-2">
                Add a Section
            </h1>
        </div>

        <div class="mt-4 max-w-xl mx-auto p-4 bg-white rounded-md shadow">
            <form :action="route('admin.sections.store')" method="post">
                <input :value="$page.props.csrf_token" name="_token" type="hidden">
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="p-2 font-bold">Number</td>
                            <td class="p-2">
                                <jet-input v-model="form.number" name="number" placeholder="11, 3A, etc..."
                                           type="text"/>
                                <input-error :message="$page.props.errors.number"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Seats</td>
                            <td class="p-2">
                                <jet-input v-model="form.seats" name="seats" type="number"/>
                                <input-error :message="$page.props.errors.seats"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Start Date</td>
                            <td class="p-2">
                                <jet-input v-model="form.start_date" name="start_date" type="date"/>
                                <input-error :message="$page.props.errors.start_date"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">End Date</td>
                            <td class="p-2">
                                <jet-input v-model="form.end_date" name="end_date" type="date"/>
                                <input-error :message="$page.props.errors.end_date"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Faculty</td>
                            <td class="p-2">
                                <jet-input v-model="form.faculty" name="faculty" type="text"/>
                                <input-error :message="$page.props.errors.faculty"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Course</td>
                            <td class="p-2">
                                <input v-model="form.course_id" name="course_id" type="hidden">
                                <v-select v-model="form.course_id" :options="courses" :reduce="course => course.id"
                                          label="name">
                                    <template v-slot:option="option">
                                        <div class="break-words">
                                            {{ option.name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ option.name_shorthand }}
                                        </div>
                                    </template>
                                </v-select>
                                <input-error :message="$page.props.errors.course_id"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Catalog</td>
                            <td class="p-2">
                                <input v-model="form.catalog_id" name="catalog_id" type="hidden">
                                <v-select v-model="form.catalog_id" :options="catalogs" :reduce="catalog => catalog.id"
                                          label="name_full">
                                    <template v-slot:option="option">
                                        {{ option.name_full }}
                                    </template>
                                </v-select>
                                <input-error :message="$page.props.errors.catalog_id"/>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
                    <jet-button>
                        Create Section
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
        courses: Array,
        catalogs: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                number: null,
                seats: null,
                start_date: null,
                end_date: null,
                faculty: null,
                course_id: null,
                catalog_id: null,
            })
        }
    },
}
</script>

<style scoped>

</style>
