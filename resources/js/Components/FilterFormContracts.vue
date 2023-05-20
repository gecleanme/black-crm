<template>

    <div class="flex flex-col justify-center items-center mt-6 mb-6 w-full md:w-3/4 lg:w-90 mx-auto xs:mb-6 py-2">
        <form @submit.prevent="filter" class="flex flex-col items-center w-full px-4 py-8 rounded-lg shadow-md bg-gray-100">
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
<!--                    <select class=" w-full sm:w-40 px-5 py-3 text-gray-700 border-->
<!--                        border-gray-400 rounded-md appearance-none-->
<!--                        focus:outline-none focus:ring-2 focus:ring-blue-400-->
<!--                        focus:border-transparent"-->
<!--                            v-model="props.type"-->
<!--                    >-->
<!--                        <option value="" selected>Type</option>-->
<!--                        <option v-for="(type,index) in types" :value="type" :key="index">{{type}}</option>-->
<!--                    </select>-->

<!--
                    <select class="w-full sm:w-auto px-3 py-2 text-gray-700 border
                     border-gray-400 rounded-md
                     appearance-none focus:outline-none
                    focus:ring-2 focus:ring-blue-400
                    focus:border-transparent"
                            v-model="props.statuses"
                    >
                        <option :value="null">Status</option>
                        <option v-for="(status,index) in statuses" :value="status" :key="index">{{status}}</option>

                    </select>

                    <select class="w-full sm:w-auto px-3 py-2 text-gray-700 border
                     border-gray-400 rounded-md
                     appearance-none focus:outline-none
                    focus:ring-2 focus:ring-blue-400
                    focus:border-transparent"
                            v-model="props.departments"
                    >
                        <option :value=null selected>Dept</option>
                        <option v-for="(department,index) in departments" :value="department" :key="index">{{department}}</option>
                    </select>

                    <select class="w-full sm:w-auto px-3 py-2 text-gray-700 border
                     border-gray-400 rounded-md
                     appearance-none focus:outline-none
                    focus:ring-2 focus:ring-blue-400
                    focus:border-transparent"
                            v-model="props.reporters"
                    >
                        <option selected value="">Reporter</option>
                        <option v-for="(reporter, index) in reporters.data" :key="index" :value="reporter.id">{{reporter.name}}</option>
                    </select>
                    -->
                    <select class=" w-full sm:w-40 px-5 py-3 text-gray-700 border
                        border-gray-400 rounded-md appearance-none
                        focus:outline-none focus:ring-2 focus:ring-blue-400
                        focus:border-transparent"
                            v-model="props.type"
                    >
                        <option value="" selected>Select Type</option>
                        <option value="Auto"> Auto </option>
                        <option value="Health"> Health </option>
                        <option value="Naval"> Naval </option>
                        <option value="Travel"> Travel </option>

                    </select>



                    <input type="text" class="w-full sm:w-auto px-3 py-2 text-gray-700 border border-gray-400
                    rounded-md appearance-none focus:outline-none focus:ring-2
                    focus:ring-blue-400 focus:border-transparent" placeholder="Search Title"
                           v-model="props.title"
                    >



                    <input type="number" class=" w-full sm:w-auto px-3 py-2 text-gray-700 border border-gray-400
                    rounded-md appearance-none focus:outline-none focus:ring-2
                    focus:ring-blue-400 focus:border-transparent" placeholder="Value Greater than"
                           v-model="props.cycle_value"
                    >

                    <input type="number" class=" w-full sm:w-auto px-3 py-2 text-gray-700 border border-gray-400
                    rounded-md appearance-none focus:outline-none focus:ring-2
                    focus:ring-blue-400 focus:border-transparent" placeholder="Premium Greater than"
                           v-model="props.premium"
                    >

                    <input type="number" class=" w-full sm:w-auto px-3 py-2 text-gray-700 border border-gray-400
                    rounded-md appearance-none focus:outline-none focus:ring-2
                    focus:ring-blue-400 focus:border-transparent" placeholder="Ends within (Days)"
                           v-model="props.ends_within"
                    >

                    <!--
                    <div class="mt-4 mx-2">
                    <label for="checkbox">Active Contracts</label>
                    <input
                        type="checkbox"
                        class="mx-2 before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border border-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-500 checked:bg-black checked:before:bg-black hover:before:opacity-10"
                        id="checkbox"
                        v-model="props.is_active"
                        :checked = props.is_active
                    />
                    </div>
                    -->

                    <v-autocomplete
                        clearable
                        @update:search="onSearch"
                        @keyup.enter="fetchCustomers"
                        label="Search for a Customer and hit Enter"
                        :items="customers"
                        item-title="name"
                        :loading="customersLoading"
                        item-value="id"
                        v-model="props.client"
                    ></v-autocomplete>


                </div>
                <div class="flex justify-center gap-2 mt-0 w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 text-white bg-black rounded-md focus:outline-none hover:bg-gray-900">Filter</button>
                    <button type="reset" class="w-full sm:w-auto px-4 py-2 text-black bg-gray-200 rounded-md focus:outline-none hover:bg-gray-300" @click="clear">Clear</button>
                </div>
            </div>
        </form>
    </div>

</template>

<script setup>

import {useForm, usePage,router} from "@inertiajs/vue3";
import {ref, inject, computed} from "vue";
import route from "ziggy-js/src/js";


const data =defineProps({
    filters:Array,
});

let props=null;


    props = useForm({

        title: data.filters.title ,
        cycle_value : data.filters.cycle_value,
        premium: data.filters.premium,
        ends_within: data.filters.ends_within,
        // new
        type: data.filters.type ?? "",
        // is_active: data.filters.is_active ?? false,
        client: data.filters.client

    });


const customers=ref([])
const searchQuery = ref('')
const customersLoading = ref(false)
function fetchCustomers() {
    customersLoading.value=true
    axios.get(`/customers?search=${searchQuery.value}`).then(({data})=>{
        customers.value=data.data
    }).finally(()=>{
        customersLoading.value=false
    })
}

function onSearch(q) {
    searchQuery.value=q;
}



const clear = () => router.visit(route(route().current()))

const filter = () => props.get('/contracts',{
   preserveScroll:true,
   preserveState:true
});




</script>

<script>
export default {
    name: "FilterFormContracts"
}
</script>

<style scoped>

</style>
