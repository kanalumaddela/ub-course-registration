<template>
    <div>
        <inertia-link :href="route('admin.index')">
            <div :class="{'bg-gray-300 font-bold': route().current('admin.index')}" class="px-3 py-5">
                Dashboard
            </div>
        </inertia-link>
        <inertia-link :href="route('admin.advisors')">
            <div :class="{'bg-gray-300 font-bold': route().current().indexOf('admin.advisors') !== -1}" class="px-3 py-5 border-t border-gray-300 hover:bg-gray-300">
                Advisors
            </div>
        </inertia-link>
        <inertia-link v-for="resource in resources" :key="`adminNav-${resource}`" :href="route('admin.' + resource + '.index')">
            <div :class="{'bg-gray-300 font-bold': route().current().indexOf('admin.' + resource + '.') !== -1}" class="px-3 py-5 border-t border-gray-300 hover:bg-gray-300">
                {{ capitalize(resource) }}
            </div>
        </inertia-link>
    </div>
</template>

<script>

const resourceTypes = [
    'index',
    'view',
    'update',
    'delete',
]

export default {
    name: "AdminNavigation",
    data() {
        return {
            resources: [
                'catalogs',
                'departments',
                'courses',
                'sections',
            ],
            resourceTypes
        }
    },
    methods: {
        capitalize(str) {
            return _.capitalize(str);
        }
    }
}
</script>

<style scoped>

</style>
