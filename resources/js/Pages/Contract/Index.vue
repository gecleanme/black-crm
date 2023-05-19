<script setup>

import TablePagination from "@/Components/TablePagination.vue";
import FilterFormContracts from "@/Components/FilterFormContracts.vue";
const props = defineProps({
    contracts: Object,
    filters: Array
})


</script>

<template>
    <h2 class="font-semibold text-3xl text-gray-600 m-4 p-2">Contracts</h2>
    <div class="overflow-x-auto">
        <FilterFormContracts :filters="props.filters"></FilterFormContracts>
        <div class="w-full sm:max-w-2xl md:max-w-4xl lg:max-w-6xl mx-auto">
            <table class="table-auto text-left text-xs sm:text-sm font-light mx-auto md:min-w-full">
                <thead class="border-b font-medium bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4">Title</th>
                    <th scope="col" class="px-6 py-4">Starts</th>
                    <th scope="col" class="px-6 py-4">Ends</th>
                    <th scope="col" class="px-6 py-4">Customer</th>
                    <th scope="col" class="px-6 py-4">Type</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!contracts" class="mt-4">
                    <td colspan="4" rowspan="5" class="text-sm text-gray-500 uppercase p-4 m-4 text-center">No Contracts Found</td>
                </tr>
                <tr v-else v-for="contract in contracts.data" :key="contract.id"
                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-100">
                    <td class="truncate px-6 py-4 font-medium">{{contract.title}}</td>
                    <td class="truncate px-6 py-4 font-medium">{{contract.last_cycle.start_date}}</td>
                    <td class="truncate px-6 py-4">{{contract.last_cycle.end_date}}</td>
                    <td class="truncate px-6 py-4">{{contract.customer.name}}</td>
                    <td class="truncate px-6 py-4">{{contract.type}}
<!--                        <div class="flex space-x-1">-->
<!--                            <PrimaryButton :link ="`/contract/${contract.id}`" icon="visibility"/>-->
<!--                            <PrimaryButton :link ="`/contract/${contract.id}/edit`" icon="edit" v-if="contract.reporter_id === $page.props.user.id"/>-->
<!--                            <PrimaryButton :link ="`/contract/${contract.id}/edit_dev`" icon="edit" v-if="contract.department === $page.props.user.department && $page.props.user.role === 'dev'"/>-->
<!--                            <ConfrimDelete :contract="contract" v-if="contract.reporter_id === $page.props.user.id"/>-->
<!--                        </div>-->


                    </td>
                </tr>
                </tbody>
            </table>
            <TablePagination v-if="contracts" :links="contracts.links"></TablePagination>
                    <!--     Links are outside data obj key       -->
        </div>

    </div>

</template>

<style scoped>

</style>

<script>
import Layout from "@/Layouts/Layout.vue";

export default {
    layout: Layout
}
</script>
