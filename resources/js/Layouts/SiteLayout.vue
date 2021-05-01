<template>
    <div>
        <div class="min-h-screen">
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

        <!-- Modal Portal -->
        <portal-target multiple name="modal"></portal-target>
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
}
</script>

<style scoped>

</style>
