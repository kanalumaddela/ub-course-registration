<template>
    <site-layout>
        <div class="py-12 max-w-screen-2xl mx-auto sm:px-6 lg:px-8">
            <paginate-cards :models="catalogs" model-route="catalogs.view" card-classes="sm:rounded bg-white overflow-x-auto"></paginate-cards>
        </div>
    </site-layout>
</template>

<script>
import SiteLayout from "@/Layouts/SiteLayout";
import PaginateCards from "@/components/PaginateCards";

export default {
    name: "Catalogs",
    components: {PaginateCards, SiteLayout},
    props: {
        catalogs: Object
    }
}
</script>

<style scoped>

</style>
