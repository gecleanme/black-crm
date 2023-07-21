<script setup>
import TablePagination from "@/Components/TablePagination.vue";
import FilterFormContracts from "@/Components/FilterFormContracts.vue";
import FlashSuccess from "@/Components/FlashSuccess.vue";
import { Link } from "@inertiajs/vue3";
import route from "ziggy-js/src/js";
const props = defineProps({
    contracts: Object,
    filters: Array,
});

const exp = () => {
    console.log(route().params);
    axios
        .post(
            route("contracts.export", props.filters),
            {},
            { responseType: "blob" },
        )
        .then((response) => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute(
                "download",
                "contract " +
                    new Date().toLocaleDateString("en-US", {
                        year: "numeric",
                        month: "numeric",
                        day: "numeric",
                    }) +
                    ".csv",
            );
            document.body.appendChild(link);
            link.click();
        })
        .catch((error) => {
            console.log(error);
        });
};
</script>

<template>
    <FlashSuccess />
    <div class="flex justify-between w-full">
        <h2 class="font-semibold text-3xl text-gray-600 m-4 p-2">Contracts</h2>
        <Link
            :href="`/contract/create`"
            class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded disabled:bg-opacity-40 m-6"
            as="button"
        >
            Add Contract</Link
        >
    </div>
    <div class="overflow-x-auto">
        <FilterFormContracts :filters="props.filters"></FilterFormContracts>
        <div class="w-full sm:max-w-2xl md:max-w-4xl lg:max-w-6xl mx-auto">
            <table
                class="table-auto text-left text-xs sm:text-sm font-light mx-auto md:min-w-full"
            >
                <thead class="border-b font-medium bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">Title</th>
                        <th scope="col" class="px-6 py-4">Starts</th>
                        <th scope="col" class="px-6 py-4">Ends</th>
                        <th scope="col" class="px-6 py-4">Value</th>
                        <th scope="col" class="px-6 py-4">Premium</th>
                        <th scope="col" class="px-6 py-4">Customer</th>
                        <th scope="col" class="px-6 py-4">Type</th>
                        <th>
                            <form @submit.prevent="exp">
                                <button
                                    class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded disabled:bg-opacity-40 m-6"
                                >
                                    Export Data
                                </button>
                            </form>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="!contracts" class="mt-4">
                        <td
                            colspan="4"
                            rowspan="5"
                            class="text-sm text-gray-500 uppercase p-4 m-4 text-center"
                        >
                            No Contracts Found
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="contract in contracts.data"
                        :key="contract.id"
                        class="border-b transition duration-300 ease-in-out hover:bg-neutral-100"
                    >
                        <td class="truncate px-6 py-4 font-medium">
                            <Link :href="`/contract/edit/${contract.id}`">
                                {{ contract.title }}
                            </Link>
                        </td>
                        <td class="truncate px-6 py-4 font-medium">
                            {{ contract.last_cycle.start_date }}
                        </td>
                        <td class="truncate px-6 py-4">
                            {{ contract.last_cycle.end_date }}
                        </td>
                        <td class="truncate px-6 py-4">
                            {{ contract.last_cycle.value }}
                        </td>
                        <td class="truncate px-6 py-4">
                            {{ contract.last_cycle.premium }}
                        </td>

                        <td class="truncate px-6 py-4">
                            {{ contract.customer.name }}
                        </td>
                        <td class="truncate px-6 py-4">
                            {{ contract.type }}
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
            <TablePagination
                v-if="contracts"
                :links="contracts.links"
            ></TablePagination>
            <!--     Links are outside data obj key       -->
        </div>
    </div>
</template>

<style scoped></style>

<script>
import Layout from "@/Layouts/Layout.vue";

export default {
    layout: Layout,
};
</script>
