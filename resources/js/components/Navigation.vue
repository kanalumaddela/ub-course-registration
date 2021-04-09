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
                <div class="flex">
                    <!-- Notifications -->
                    <jet-dropdown width="96" align="center" trigger-classes="bg-purple-900" content-classes="bg-white">
                        <template #trigger>
                            <button class="p-1 rounded-full text-gray-300 hover:text-white focus:text-white focus:outline-none focus:bg-purple-900">
                                <span class="sr-only">View notifications</span>
                                <!-- Heroicon name: outline/bell -->
                                <div class="relative">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span v-if="$page.props.user.notifications.unreadCount" class="absolute -top-2.5 -right-2.5 h-5 w-5 leading-5 text-xs rounded-full text-white bg-red-600">{{ $page.props.user.notifications.unreadCount }}</span>
                                </div>
                            </button>
                        </template>
                        <template #content>
                            <div class="block text-gray-400">
                                <div class="py-2 px-4 flex justify-end">
                                    <a href="#" class="px-2 py-1 rounded-tl rounded-bl text-center bg-red-500 text-white">Clear all</a>
                                    <a href="#" class="px-2 py-1 rounded-tr rounded-br text-center bg-purple-800 text-white">Mark all read</a>
                                </div>
                                <hr>

                                <div v-for="(item, index) in $page.props.user.notifications.all" :key="`notification-${item.id}`">
                                    <div class="py-3 px-4" :class="{'font-bold': !item.read_at}">
                                        <span v-if="!item.read_at" class="px-2 py-0.5 rounded bg-green-500 text-white">New</span>
                                        <a :href="route('notifications', item.id)" class="hover:underline">
                                            {{ item.data.text }}
                                        </a>
                                    </div>
                                    <hr v-if="index < 4">
                                </div>
                            </div>
                        </template>
                    </jet-dropdown>

                    <!-- Profile -->
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
