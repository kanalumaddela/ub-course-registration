<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto"></div>

        <div class="mt-4 max-w-xl mx-auto p-4 bg-white rounded-md shadow">
            <form :action="route('admin.courses.store')" method="post">
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
                                <textarea v-model="form.description" class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" name="description" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
                    <jet-button>
                        Add Course
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
        JetCheckbox,
        vSelect,
    },
    props: {
        departments: Array,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: null,
                number: null,
                description: null,
                credits: null,
                department_id: null,
            })
        }
    },
}
</script>

<style scoped>

</style>
