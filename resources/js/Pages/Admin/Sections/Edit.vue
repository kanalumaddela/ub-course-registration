<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.catalogs.show', catalog)"
                          class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                </svg>
                Back to Catalog
            </inertia-link>
        </div>

        <div class="mt-4 max-w-sm mx-auto p-4 bg-white rounded-md shadow">
            <form :action="route('admin.catalogs.update', catalog)" method="post">
                <input name="_method" type="hidden" value="PUT">
                <input :value="$page.props.csrf_token" name="_token" type="hidden">
                <table>
                    <tbody>
                        <tr>
                            <td class="p-2 font-bold">
                                Active
                            </td>
                            <td class="p-2">
                                <jet-checkbox v-model="editForm.is_active" name="is_active" value="1" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Name</td>
                            <td class="p-2">
                                <jet-input v-model="editForm.name" type="text" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Semester</td>
                            <td class="p-2">
                                <select v-model="editForm.semester" class="rounded mt-1 block w-full" name="semester">
                                    <option value="spring">Spring</option>
                                    <option value="summer">Summer</option>
                                    <option value="fall">Fall</option>
                                    <option value="winter">Winter</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Year</td>
                            <td class="p-2">
                                <jet-input v-model="editForm.year" name="year" type="text" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
                    <inertia-link :href="route('admin.catalogs.show', catalog)">
                        <jet-danger-button type="button">
                            Go Back
                        </jet-danger-button>
                    </inertia-link>
                    <jet-button>
                        Save Catalog
                    </jet-button>
                </div>
            </form>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import JetButton from '@/Jetstream/Button';
import JetDangerButton from '@/Jetstream/DangerButton';
import JetInput from "@/Jetstream/Input";
import JetCheckbox from "@/Jetstream/Checkbox";

export default {
    name: "Edit",
    components: {
        JetInput,
        AdminLayout,
        JetButton,
        JetDangerButton,
        JetCheckbox,
    },
    props: {
        catalog: Object
    },
    data() {
        return {
            editForm: this.$inertia.form({
                name: this.catalog.name,
                semester: this.catalog.semester,
                year: this.catalog.year,
                is_active: Boolean(this.catalog.is_active),
            }),
        }
    },
    methods: {
        modelToFormData() {
            return {
                name: this.catalog.name,
                semester: this.catalog.semester,
                year: this.catalog.year,
                is_active: Boolean(this.catalog.is_active),
            };
        }
    }
}
</script>

<style scoped>

</style>
