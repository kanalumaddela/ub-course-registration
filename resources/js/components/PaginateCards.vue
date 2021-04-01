<template>
    <div>
        <pagination :paginator="models" />

        <div class="py-2 grid" :class="classes">
            <inertia-link v-if="useInertia" :href="route(modelRoute, model.id)" :key="model.id" v-for="model in models.data">
                <card :data="model" :class="cardClasses"></card>
            </inertia-link>
            <a v-else>
                <h1>no</h1>
            </a>
        </div>
    </div>
</template>

<script>
import Card from "@/components/Card";
import Pagination from "@/components/Pagination";

export default {
    name: "PaginateCards",
    components: {
        Card,
        Pagination,
    },
    props: {
        classes: {
            type: String,
            default: 'grid-cols-1 gap-4 lg:grid-cols-3 sm:grid-cols-2'
        },
        cardClasses: String,
        models: Object,
        modelRoute: String,
        useInertia: {
            type: Boolean,
            default: true
        }
    },
}
</script>

<style scoped>

</style>
