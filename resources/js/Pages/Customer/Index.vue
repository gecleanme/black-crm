<script setup>

import TablePagination from "@/Components/TablePagination.vue";
import FilterForm from "@/Components/FilterForm.vue";
import {Link} from "@inertiajs/vue3";
import FlashSuccess from "@/Components/FlashSuccess.vue";

const props = defineProps({
    customers: Object,
    filters:Array,
    types:Array
})

const exp = () => {
    axios.post('/customer/export', {}, { responseType: 'blob' })
        .then(response => {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'customer.csv');
            document.body.appendChild(link);
            link.click();
        })
        .catch(error => {
            console.log(error);
        });
};


</script>

<template>
    <FlashSuccess/>
    <div class="flex justify-between w-full">
    <h2 class="font-semibold text-3xl text-gray-600 m-4 p-2">Customers</h2>
    <Link :href="`/customer/create`"
          class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded disabled:bg-opacity-40 m-6"
          as="button"
    > Add Customer</Link>

    </div>

    <div class="overflow-x-auto">

        <FilterForm :types="props.types" :filters="props.filters"></FilterForm>


        <div class="w-full sm:max-w-2xl md:max-w-4xl lg:max-w-6xl mx-auto">
            <table class="table-auto text-left text-xs sm:text-sm font-light mx-auto md:min-w-full">
                <thead class="border-b font-medium bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-4">Name</th>
                    <th scope="col" class="px-6 py-4">Primary Phone</th>
                    <th scope="col" class="px-6 py-4">Notes</th>
                    <th scope="col" class="px-6 py-4">VIP?</th>
                    <th>
                        <form @submit.prevent="exp" >
                            <button
                                class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded disabled:bg-opacity-40 m-6"
                            >Export Data</button>
                        </form>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="!customers" class="mt-4">
                    <td colspan="4" rowspan="5" class="text-sm text-gray-500 uppercase p-4 m-4 text-center">No Customers Found</td>
                </tr>
                <tr v-else v-for="customer in customers.data" :key="customer.id"
                    class="border-b transition duration-300 ease-in-out hover:bg-neutral-100">
                    <td class="truncate px-6 py-4 font-medium"> <Link :href="`/customer/edit/${customer.id}`">{{ customer.name }}</Link>
                    </td>
                    <td class="truncate px-6 py-4">{{customer.cell}}</td>
                    <td class="truncate px-6 py-4">{{customer.notes}}</td>
                    <td class="truncate px-6 py-4">
                        {{customer.vip ? 'Yes': 'No'}}
<!--                        <div class="flex space-x-1">-->
<!--                            <PrimaryButton :link ="`/customer/${customer.id}`" icon="visibility"/>-->
<!--                            <PrimaryButton :link ="`/customer/${customer.id}/edit`" icon="edit" v-if="customer.reporter_id === $page.props.user.id"/>-->
<!--                            <PrimaryButton :link ="`/customer/${customer.id}/edit_dev`" icon="edit" v-if="customer.department === $page.props.user.department && $page.props.user.role === 'dev'"/>-->
<!--                            <ConfrimDelete :customer="customer" v-if="customer.reporter_id === $page.props.user.id"/>-->
<!--                        </div>-->


                    </td>
                </tr>
                </tbody>
            </table>
            <TablePagination v-if="customers" :links="customers.links"></TablePagination>
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
