<template>
    <site-layout>
        <div class="py-12 max-w-screen-md mx-auto sm:px-6 lg:px-8">
            <div class="p-4 bg-white rounded-md shadow">
                <div class="flex items-center p-5 justify-between">
                    <div class="flex items-center">
                        <img :alt="person.name" :src="person.profile_photo_url_large"
                             class="mr-2 h-24 w-24 rounded-full object-cover">
                        <div>
                            <h1 class="font-medium text-xl">
                                {{ person.name }}
                            </h1>
                            <h1 class="text-gray-400">{{ person.email }}</h1>

                            <template v-if="person.roles.length">
                                <hr class="my-1">

                                <h1>{{ person.roles[0].name }}</h1>
                            </template>
                        </div>
                    </div>
                    <div class="grid gap-1.5">
                        <inertia-link :href="route('messages.index', {to: person.id})">
                            <jet-button class="w-full justify-center">
                                Message
                                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                            </jet-button>
                        </inertia-link>
                    </div>
                </div>
            </div>
        </div>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import JetButton from "@/Jetstream/Button";

export default {
    name: "Profile",
    components: {
        SiteLayout,
        JetButton,
    },
    props: {
        person: Object
    }
}
</script>

<style scoped>

</style>
