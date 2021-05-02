<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <div class="grid grid-cols-4 gap-x-4">
                <div class="col-span-1">
                    <div class="p-4 bg-white rounded-md shadow mb-2">
                        <h1 class="text-lg uppercase font-bold flex items-center">
                            <svg class="h-6 w-6 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                            Add an advisor
                        </h1>
                        <hr class="my-2">

                        <div>
                            <form :action="route('admin.advisors.create')" method="post">
                                <input :value="$page.props.csrf_token" name="_token" type="hidden" />
                                <input v-model="addAdvisorForm.advisor" name="user_id" type="hidden" />
                                <input v-model="addAdvisorForm.department" name="department_id" type="hidden" />
                                <div class="mb-3">
                                    <label class="font-bold">Advisor</label>
                                    <v-select v-model="addAdvisorForm.advisor" :options="advisors" :reduce="user => user.id" label="name">
                                        <template v-slot:option="option">
                                            <div class="flex items-center py-2">
                                                <img :alt="option.name" :src="option.profile_photo_url" class="flex-shrink-0 h-6 w-6 rounded-full">
                                                <span class="font-normal ml-3 block truncate">
                                                {{ option.name }}
                                            </span>
                                            </div>
                                        </template>
                                    </v-select>
                                </div>
                                <div>
                                    <label class="font-bold">Department</label>
                                    <v-select v-model="addAdvisorForm.department" :options="departments" :reduce="department => department.id" label="name">
                                        <template v-slot:option="option">
                                            [{{ option.prefix }}] {{ option.name }}
                                        </template>
                                    </v-select>
                                </div>

                                <div class="mt-4 text-right">
                                    <jet-button type="submit">Add Advisor</jet-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-span-3">
                    <pagination :paginator="departmentAdvisors" class="shadow" />

                    <div class="flex flex-col mb-2">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" scope="col">
                                                    Name
                                                </th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" scope="col">
                                                    Departments
                                                </th>
                                                <th class="relative px-6 py-3" scope="col">
                                                    <span class="sr-only">Edit</span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="departmentAdvisor in departmentAdvisors.data" :key="`department-advisor-${departmentAdvisor.id}`">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <img :alt="departmentAdvisor.name" :src="departmentAdvisor.profile_photo_url" class="h-10 w-10 rounded-full">
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ departmentAdvisor.name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                {{ departmentAdvisor.email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div v-for="(department, index) in departmentAdvisor.departments" v-if="index < 2" class="text-sm text-gray-900">
                                                        {{ `${department.prefix} - ${department.name}` }}<span v-if="index !== 1 && (index + 1) !== departmentAdvisor.departments.length">,</span>
                                                        <div v-if="index === 1">...more</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <inertia-link :href="route('admin.advisors.view', departmentAdvisor)" class="text-indigo-600 hover:text-indigo-900">
                                                        Edit
                                                    </inertia-link>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <pagination :paginator="departmentAdvisors" class="shadow" />
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import Pagination from "@/components/Pagination";
import JetInput from '@/Jetstream/Input';
import JetButton from '@/Jetstream/Button';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: "Advisors",
    components: {
        Pagination,
        AdminLayout,
        JetInput,
        JetButton,
        vSelect,
    },
    props: {
        advisors: Array,
        departments: Array,
        departmentAdvisors: Object,
    },
    data() {
        return {
            addAdvisorForm: this.$inertia.form({
                advisor: null,
                department: null,
            }),
        }
    },
}
</script>

<style scoped>

</style>
