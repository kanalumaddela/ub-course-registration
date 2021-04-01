<template>
    <inertia-link :href="href" :class="classes">
        <slot></slot>
    </inertia-link>
</template>

<script>
    export default {
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                            ? 'px-3 py-2 text-sm font-medium bg-purple-900 text-white rounded-md transition duration-150 ease-in-out' // active
                            : 'px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-purple-900 rounded-md transition duration-150 ease-in-out' // inactive
            }
        }
    }
</script>
