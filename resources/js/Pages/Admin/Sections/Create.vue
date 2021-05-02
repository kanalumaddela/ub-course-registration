<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto"></div>

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
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Name</td>
                            <td class="p-2">
                                <jet-input v-model="form.name" type="text" />
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
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Year</td>
                            <td class="p-2">
                                <jet-input v-model="form.year" name="year" type="text" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
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
import JetInput from "@/Jetstream/Input";
import JetButton from "@/Jetstream/Button";
import JetCheckbox from "@/Jetstream/Checkbox";

export default {
    name: "Create",
    components: {
        JetInput,
        AdminLayout,
        JetButton,
        JetCheckbox,
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
