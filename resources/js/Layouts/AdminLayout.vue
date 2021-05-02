<template>
    <site-layout>
        <div class="grid grid-cols-8" style="min-height: calc(100vh - 64px)">
            <!-- sidenav -->
            <div class="col-span-1 border-r border-gray-300 bg-white">
                <admin-navigation />
            </div>

            <!-- content -->
            <div class="col-span-7">
                <slot />
            </div>
        </div>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import AdminNavigation from "@/components/AdminNavigation";

export default {
    name: "AdminLayout",
    components: {AdminNavigation, SiteLayout},
}
</script>

<style scoped>

</style>
