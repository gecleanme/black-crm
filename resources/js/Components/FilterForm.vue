<template>

    <div class="flex flex-col justify-center items-center mt-6 mb-6 w-full md:w-3/4 lg:w-90 mx-auto xs:mb-6 py-2">
        <form @submit.prevent="filter" class="flex flex-col items-center w-full px-4 py-8 rounded-lg shadow-md bg-gray-100">
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                    <select class=" w-full sm:w-40 px-5 py-3 text-gray-700 border
                        border-gray-400 rounded-md appearance-none
                        focus:outline-none focus:ring-2 focus:ring-blue-400
                        focus:border-transparent"
                            v-model="props.type"
                    >
                        <option value="" selected>Type</option>
                        <option v-for="(type,index) in types" :value="type" :key="index">{{type}}</option>
                    </select>

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
                            v-model="props.risk_level"
                    >
                        <option value="" selected>Risk Level</option>
                        <option value="0"> Very Low </option>
                        <option value="25"> Low </option>
                        <option value="50"> Moderate </option>
                        <option value="75"> High </option>
                        <option value="100"> Very High </option>




                    </select>



                    <input type="text" class="w-full sm:w-auto px-3 py-2 text-gray-700 border border-gray-400
                    rounded-md appearance-none focus:outline-none focus:ring-2
                    focus:ring-blue-400 focus:border-transparent" placeholder="Search Name"
                           v-model="props.name"
                    >
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

import {router, useForm, usePage} from "@inertiajs/vue3";
import {ref ,inject} from "vue";
import route from "ziggy-js/src/js";


const data =defineProps({
    filters:Array,
    vip: {type: Boolean, required:false},
    types: Array,
    hasNotes:{type: Boolean, required:false}

});

let props=null;


    props = useForm({
        type: data.filters.type ?? "",
        name: data.filters.name ?? null,
        risk_level: data.filters.risk_level ?? ""
    });


const clear = () => router.visit(route(route().current()))

const filter = () => props.get('/customers',{
   preserveScroll:true,
   preserveState:true
});




</script>

<script>
export default {
    name: "FilterForm"
}
</script>

<style scoped>

</style>
