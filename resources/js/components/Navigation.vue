<template>
    <nav class="bg-purple-800">
        <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Left Side -->
                <div class="flex">
                    <!-- Logo / Site Name -->
                    <div class="flex items-center">
                        <inertia-link :href="route('index')" class="pr-3.5 text-white">
                            UB Course Registration
                        </inertia-link>

                        <div class="h-full w-0.5 bg-white"></div>
                    </div>

                    <div class="flex space-x-3 sm:ml-3.5">
                        <jet-nav-link :href="route('index')" :active="route().current('index')">
                            Home
                        </jet-nav-link>
                        <jet-nav-link :href="route('search')" :active="route().current('search')">
                            Search
                        </jet-nav-link>
                        <jet-nav-link :active="route().current().indexOf('users.') !== -1" :href="route('users.index')">
                            User Directory
                        </jet-nav-link>

                        <div v-show="hasRole('advisor')" class="h-full w-0.5 bg-white"></div>

                        <jet-nav-link v-show="hasRole('advisor')" :active="route().current().indexOf('advisor.') !== -1"
                                      :href="route('advisor.registrations')">
                            Advisor
                        </jet-nav-link>
                        <jet-nav-link v-show="hasRole('admin')" :active="route().current().indexOf('admin.') !== -1"
                                      :href="route('admin.index')">
                            Admin
                        </jet-nav-link>
                    </div>
                </div>

                <!-- Right Side -->
                <div v-if="$page.props.user" class="flex items-center">
                    <!-- Messages -->
                    <inertia-link :class="{'bg-purple-900': route().current('messages.index') || route().current('messages.view')}" class="p-1 rounded-full text-gray-300 hover:text-white focus:text-white focus:outline-none focus:bg-purple-900" href="/messages">
                        <span class="sr-only">View Messages</span>
                        <!-- Heroicon name: outline/inbox -->
                        <div class="relative">
                            <svg aria-hidden="true" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" stroke-linecap="round" stroke-linejoin="round" stroke-width=2 />
                            </svg>
                            <span v-if="$page.props.user.unreadMessageCount" :class="{'leading-6': $page.props.user.unreadMessageCount > 100}" class="absolute -top-2.5 -right-2.5 z-10 h-5 w-5 leading-5 text-center text-xs rounded-full text-white bg-red-600">{{ $page.props.user.unreadMessageCount < 100 ? $page.props.user.unreadMessageCount : '*' }}</span>
                        </div>
                    </inertia-link>

                    <!-- Notifications -->
                    <jet-dropdown width="96" align="center" trigger-classes="bg-purple-900" content-classes="bg-white">
                        <template #trigger>
                            <button :class="{'bg-purple-900': route().current('notifications')}" class="p-1 rounded-full text-gray-300 hover:text-white focus:text-white focus:outline-none focus:bg-purple-900">
                                <span class="sr-only">View notifications</span>
                                <!-- Heroicon name: outline/bell -->
                                <div class="relative">
                                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span v-if="$page.props.user.notifications.unreadCount" class="absolute -top-2.5 -right-2.5 z-10 h-5 w-5 leading-5 text-xs rounded-full text-white bg-red-600">{{ $page.props.user.notifications.unreadCount }}</span>
                                </div>
                            </button>
                        </template>
                        <template #content>
                            <div class="block text-gray-400">
                                <div v-if="$page.props.user.notifications.all.length">

                                    <div class="py-2 px-4 flex justify-end">
                                        <inertia-link :href="route('notifications.clearAll')" as="button" class="px-2 py-1 rounded-tl-md rounded-bl-md text-center bg-red-500 text-white" method="post">
                                            Clear all
                                        </inertia-link>
                                        <inertia-link :href="route('notifications.markAllRead')" as="button" class="px-2 py-1 rounded-tr-md rounded-br-md text-center bg-purple-800 text-white" method="post">
                                            Mark all read
                                        </inertia-link>
                                    </div>

                                    <hr>

                                    <div v-for="(item, index) in $page.props.user.notifications.all" :key="`notification-${item.id}`">

                                        <div :class="{'font-bold': !item.read_at}" class="py-3 px-4 flex items-center">
                                            <span v-if="!item.read_at" class="px-2 py-0.5 mr-2 rounded bg-green-500 text-white">New</span>
                                            <div>
                                                <a :href="route('notifications.view', item.id)" class="hover:underline">
                                                    {{ item.data.text }}
                                                </a>
                                                <div class="text-sm text-gray-300">{{ luxonFormatFriendly(item.created_at) }}</div>
                                            </div>
                                        </div>
                                        <hr v-if="index < 4">
                                    </div>
                                    <div v-if="$page.props.user.notifications.totalCount > 5" class="py-2 text-center">
                                        <inertia-link class="text-purple-600 hover:underline" href="/notifications">
                                            View All
                                        </inertia-link>
                                    </div>
                                </div>
                                <div v-else class="py-3 px-4">
                                    <p>No notifications...</p>
                                </div>
                            </div>
                        </template>
                    </jet-dropdown>

                    <div class="h-full w-1 bg-red-600 rounded"></div>

                    <!-- Profile -->
                    <jet-dropdown align="right" v-if="$page.props.user">
                        <template #trigger>
                            <span class="inline-flex rounded-md">
                                <button class="inline-flex items-center px-3 py-2 text-sm leading-4 font-medium text-white hover:text-gray-300 focus:outline-none transition ease-in-out duration-150" type="button">
                                    <img v-if="$page.props.jetstream.managesProfilePhotos" :alt="$page.props.user.name" :src="$page.props.user.profile_photo_url" class="mr-1.5 h-8 w-8 rounded-full object-cover" />

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

                <div v-else class="flex items-center">
                    <jet-nav-link :href="route('login')">
                        Login
                    </jet-nav-link>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
import JetNavLink from "@/Jetstream/NavLink";
import JetDropdown from '@/Jetstream/Dropdown';
import JetDropdownLink from '@/Jetstream/DropdownLink'
import {DateTime} from "luxon";

export default {
    name: "Navigation",
    components: {
        JetNavLink,
        JetDropdown,
        JetDropdownLink,
    },
    methods: {
        luxonFormatFriendly(timestamp) {
            return DateTime.fromISO(timestamp).toRelative();
        },
        logout() {
            this.$inertia.post(route('logout'));
        },
    }
}
</script>

<style scoped>

</style>
