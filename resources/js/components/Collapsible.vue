<template>
    <div>
        <div class="relative" :class="headerClasses" @click="openContent">
            <div class="flex items-center">
                <div class="flex-grow cursor-pointer">
                    <slot name="header"></slot>
                </div>
                <svg class="w-8 transform" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <div :class="getContentClasses">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "Collapsible",
    props: {
        // open: {
        //     type: Boolean,
        //     default: false,
        // },
        headerClasses: {
            type: String,
            default: 'p-3 bg-gray-200'
        },
        contentClasses: {
            type: String,
            default: 'p-3'
        },
    },
    data() {
        return {
            open: false,
        }
    },
    methods: {
        openContent() {
            this.open = !this.open;
        }
    },
    computed: {
        getContentClasses() {
            return this.contentClasses + (this.open ? '' : ' hidden');
        }
    }
}
</script>

<style>
</style>
