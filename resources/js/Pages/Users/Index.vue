<template>
    <site-layout>
        <div class="py-12 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <pagination :paginator="users"/>

            <!-- This example requires Tailwind CSS v2.0+ -->
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-full"
                                            scope="col">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            scope="col">
                                            Role
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                            scope="col">
                                            Joined
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in users.data">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <inertia-link :href="route('users.view', user)">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <img :alt="user.name" :src="user.profile_photo_url"
                                                             class="h-10 w-10 rounded-full">
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ user.name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ user.email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </inertia-link>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <template v-if="user.roles.length">
                                                {{ user.roles[0].name }}
                                            </template>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ luxonFormatFriendly(user.created_at) }}
                                        </td>
                                    </tr>

                                    <!-- More people... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import Pagination from "@/components/Pagination";
import {DateTime} from "luxon";

export default {
    name: "Index",
    components: {
        Pagination,
        SiteLayout
    },
    props: {
        users: Object,
    },
    methods: {
        luxonFormatFriendly(timestamp) {
            return DateTime.fromISO(timestamp).toRelative();
        },
    }
}
</script>

<style scoped>

</style>
