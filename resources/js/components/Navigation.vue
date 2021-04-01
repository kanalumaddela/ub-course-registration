<template>
    <nav class="bg-purple-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Left Side -->
                <div class="flex">
                    <!-- Logo / Site Name -->
                    <div class="flex items-center">
                        <inertia-link :href="route('index')" class="pr-3.5 text-white border-r-2 border-white">
                            UB Course Registration
                        </inertia-link>
                    </div>
                    <div class="flex space-x-3 sm:ml-3.5">
                        <jet-nav-link :href="route('index')" :active="route().current('index')">
                            Home
                        </jet-nav-link>
                        <jet-nav-link :href="route('search')" :active="route().current('search')">
                            Search
                        </jet-nav-link>
                        <jet-nav-link :href="route('catalogs.index')" :active="route().current('catalogs.index')">
                            Catalogs
                        </jet-nav-link>
                        <jet-nav-link :href="route('test.courses')" :active="route().current('test.courses')">
                            Courses
                        </jet-nav-link>
                        <jet-nav-link :href="route('dashboard')" :active="false">
                            Registration
                        </jet-nav-link>
                        <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')" v-if="$page.props.user">
                            Dashboard
                        </jet-nav-link>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="">
                    <!-- Profile/Notifications -->
                    <jet-dropdown align="right" v-if="$page.props.user">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button type="button" class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium rounded-md text-white hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                    {{ $page.props.user.name }}

                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </span>
                        </template>
                        <template #content>
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Manage Account
                            </div>

                            <jet-dropdown-link :href="route('profile.show')">
                                Profile
                            </jet-dropdown-link>

                            <!-- Authentication -->
                            <form @submit.prevent="logout">
                                <jet-dropdown-link as="button">
                                    Logout
                                </jet-dropdown-link>
                            </form>
                        </template>
                    </jet-dropdown>

                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import JetNavLink from "@/Jetstream/NavLink";
import JetDropdown from '@/Jetstream/Dropdown';
import JetDropdownLink from '@/Jetstream/DropdownLink'

export default {
    name: "Navigation",
    components: {
        JetNavLink,
        JetDropdown,
        JetDropdownLink,
    },
}
</script>

<style scoped>

</style>
