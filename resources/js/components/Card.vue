<template>
    <div class="shadow overflow-hidden" :class="classes">
        <slot>
            <pre class="p-6">{{ data }}</pre>
        </slot>
    </div>
</template>

<script>
export default {
    name: "Card",
    props: {
        data: Object,
        classes: String,
    }
}
</script>

<style scoped>

</style>
