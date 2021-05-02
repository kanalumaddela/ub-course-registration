<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <div class="p-4 bg-white rounded-md shadow">
                <div class="py-2 flex items-center">
                    <img v-if="$page.props.jetstream.managesProfilePhotos" :alt="advisor.name" :src="advisor.profile_photo_url_large" class="mr-4 h-24 w-24 rounded-full object-cover" />
                    <div>
                        <h1 class="text-xl">{{ advisor.name }}</h1>
                        <h1 class="-mt-1 text-gray-400">{{ advisor.email }}</h1>
                    </div>
                </div>

                <hr class="my-2">

                <div class="grid grid-cols-6 gap-3">
                    <div class="col-span-full py-2 flex items-center justify-between">
                        <h1 class="flex items-center text-xl font-bold">
                            <svg class="h-7 w-7 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
                            Departments
                        </h1>

                        <jet-button type="button" @click.native="updateDepartments">Update Departments</jet-button>
                    </div>
                    <div v-for="department in departments" class="col-span-1">
                        <label class="flex items-center">
                            <jet-checkbox v-model="advisorDepartments" :value="department.id" name/>
                            <div class="ml-2">
                                <div class="text-sm text-gray-600">{{ department.name }}</div>
                                <div class="text-xs text-gray-400">{{ department.prefix }}</div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import JetCheckbox from '@/Jetstream/Checkbox';
import JetButton from '@/Jetstream/Button';

export default {
    name: "Advisor",
    components: {
        AdminLayout,
        JetCheckbox,
        JetButton,
    },
    props: {
        advisor: Object,
        departments: Array,
    },
    data() {
        return {
            advisorDepartments: [],
            advisorCurrentDepartments: {},
        }
    },
    mounted() {
        this.advisor.departments.forEach(department => {
            this.advisorDepartments.push(department.id);
        });
    },
    methods: {
        updateDepartments() {
            this.$inertia.post(route('admin.advisors.update', this.advisor), {
                department_ids: this.advisorDepartments,
                // _token: this.$page.props.csrf_token,
            });
        }
    }
}
</script>

<style scoped>

</style>
