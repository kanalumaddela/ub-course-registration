<template>
    <admin-layout>
        <div class="mt-4 max-w-screen-2xl mx-auto">
            <inertia-link :href="route('admin.departments.show', department)" class="flex items-center text-purple-700 hover:underline">
                <svg class="h-6 w-6 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11 17l-5-5m0 0l5-5m-5 5h12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /></svg>
                Back to Department
            </inertia-link>
        </div>

        <div class="mt-4 max-w-sm mx-auto p-4 bg-white rounded-md shadow">
            <form :action="route('admin.departments.update', department)" method="post">
                <input name="_method" type="hidden" value="PUT">
                <input :value="$page.props.csrf_token" name="_token" type="hidden">
                <table class="w-full">
                    <tbody>
                        <tr>
                            <td class="p-2 font-bold">Name</td>
                            <td class="p-2">
                                <jet-input v-model="form.name" name="name" type="text" />
                                <input-error :message="$page.props.errors.name" />
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2 font-bold">Prefix</td>
                            <td class="p-2">
                                <jet-input v-model="form.prefix" name="prefix" type="text" />
                                <input-error :message="$page.props.errors.prefix" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-2 text-center">
                    <inertia-link :href="route('admin.departments.show', department)">
                        <jet-danger-button type="button">
                            Go Back
                        </jet-danger-button>
                    </inertia-link>
                    <jet-button>
                        Save Department
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
import JetDangerButton from "@/Jetstream/DangerButton";

export default {
    name: "Create",
    components: {
        Input,
        JetInput,
        AdminLayout,
        JetButton,
        JetCheckbox,
        JetDangerButton,
    },
    props: {
        department: Object
    },
    data() {
        return {
            form: this.$inertia.form({
                name: this.department.name,
                prefix: this.department.prefix,
            })
        }
    }
}
</script>

<style scoped>

</style>
