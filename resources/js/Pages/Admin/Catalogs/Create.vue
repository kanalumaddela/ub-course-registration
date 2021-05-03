<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.catalogs.index')"
                          class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round"
                          stroke-width="2"/>
                </svg>
                Back to Results
            </inertia-link>
        </div>

        <div class="text-center">
            <h1 class="text-2xl uppercase font-black mb-2">
                Create a Catalog
            </h1>
        </div>

        <div class="mt-4 max-w-sm mx-auto p-4 bg-white rounded-md shadow">
            <form :action="route('admin.catalogs.store')" method="post">
                <input :value="$page.props.csrf_token" name="_token" type="hidden">
                <table>
                    <tbody>
                        <tr>
                            <td class="p-2 font-bold">
                                Active
                            </td>
                            <td class="p-2">
                                <jet-checkbox v-model="form.is_active" name="is_active" value="1" />
                                <input-error :message="$page.props.errors.is_active" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Name</td>
                            <td class="p-2">
                                <jet-input v-model="form.name" name="name" type="text" />
                                <input-error :message="$page.props.errors.name" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Semester</td>
                            <td class="p-2">
                                <select v-model="form.semester" class="rounded mt-1 block w-full" name="semester">
                                    <option disabled selected value="null">Choose a semester</option>
                                    <option value="spring">Spring</option>
                                    <option value="summer">Summer</option>
                                    <option value="fall">Fall</option>
                                    <option value="winter">Winter</option>
                                </select>
                                <input-error :message="$page.props.errors.semester" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Year</td>
                            <td class="p-2">
                                <jet-input v-model="form.year" name="year" type="text" />
                                <input-error :message="$page.props.errors.year" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
                    <jet-button>
                        Add Catalog
                    </jet-button>
                </div>
            </form>
        </div>
    </admin-layout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout";
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import JetCheckbox from "@/Jetstream/Checkbox";
import InputError from "@/Jetstream/InputError";

export default {
    name: "Create",
    components: {
        JetInput,
        AdminLayout,
        JetButton,
        JetCheckbox,
        InputError,
    },
    data() {
        return {
            form: this.$inertia.form({
                name: null,
                semester: null,
                year: (new Date).getFullYear(),
                is_active: true,
            })
        }
    }
}
</script>

<style scoped>

</style>
