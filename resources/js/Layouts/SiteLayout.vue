<template>
    <div v-bind:class="{dark: darkMode}">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navigation -->
            <navigation></navigation>

            <!-- Header -->
            <slot name="header"></slot>

            <!-- Content -->
            <main>
                <slot></slot>
            </main>

            <!-- Footer -->
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>

import Navigation from "@/components/Navigation";
export default {
    name: "SiteLayout",
    components: {Navigation},
    data() {
        return {
            showingNavigationDropdown: false,
            darkMode: false,
        }
    },
    methods: {
        logout() {
            this.$inertia.post(route('logout'));
        },
    },
    mounted() {
        if (this.$page.props.user.notifications) {
            console.log(this.$page.props.user.notifications);
        }
    }
}
</script>

<style scoped>

</style>
