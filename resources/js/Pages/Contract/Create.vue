<script setup xmlns="http://www.w3.org/1999/html">

import {inject, onMounted, ref} from "vue";

import {useForm} from "@inertiajs/vue3";
import CreateCycle from "@/Pages/Cycle/Create.vue";
const formData = useForm({
//create form and v-model the elements
    title: null,
    make:"",
    client:"",
    notes:null,
    prod_year:"",
    type:"Auto",
    model:"",
    regno:null,
    pnum:null,
    lic_exp:null
});

let props = defineProps({

    makes:Array,
    models:Array

})

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

function range(start, end) {
    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
}





//
// const attachmentPreview= ref( formData.attachments)
//
//
// const packFiles = (event) => {
//     for(const att of event.target.files){
//         formData.attachments=att;
//     }
//
//
//     let file= event.target.files[0]
//     if(!file){
//         return
//     }
//
//     const  reader = new FileReader()
//     reader.onload=(e)=>{
//         attachmentPreview.value=e.target.result
//     }
//
//     reader.readAsDataURL(file)
//
//
// }

const reset = () => formData.reset();

const sendData = () => formData.post('/contract/store',{
    onSuccess: () => formData.reset()
});

</script>

<script>
import Layout from "@/Layouts/Layout.vue";
import {Head} from "@inertiajs/vue3";

export default {
    layout: Layout
}
</script>



<template>

    <Head title="Contract Create" />
    <form @submit.prevent="sendData">

        <!-- Template Start   -->
        <!-- component -->
        <!-- Alert    -->
        <div class="min-h-screen p-6 bg-gray-100 flex items-center justify-center "  v-if="!formData.wasSuccessful">
            <div class="container max-w-screen-lg mx-auto">
                <div>
                    <h2 class="font-semibold text-xl text-gray-600">Add a Contract</h2>
                    <p class="text-gray-500 mb-6">Required fields are marked with <span class="text-sm text-red-500">* </span></p>

                    <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                            <div class="text-gray-600">
                                <p class="font-medium text-lg">Contract Details</p>
                            </div>

                            <div class="lg:col-span-2">
                                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5 rounded-md border-white outline-white">


                                    <div class="md:col-span-5">
                                        <label for="full_name" class="font-semibold">Title</label>
                                        <input type="text" v-model="formData.title" id="full_name" class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"/>
                                        <p v-if="formData.errors.title" class="text-sm text-red-500 font-semibold">{{formData.errors.title}}</p>
                                    </div>


                                    <div class="md:col-span-2 block border-r-2 ml-2 px-2">

                                        <label class="text-black font-semibold">
                                           Insurance Type
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text">Auto</span>
                                            <input type="radio" v-model="formData.type" class="bg-gray-100 checked: radio " value="Auto"  checked />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text">Travel</span>
                                            <input type="radio" v-model="formData.type" class="bg-gray-100 checked: radio " value="Travel" />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text">Health</span>
                                            <input type="radio" v-model="formData.type" class="bg-gray-100 checked: radio " value="Health"  />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text">Naval</span>
                                            <input type="radio" v-model="formData.type" class="bg-gray-100 checked: radio " value="Naval" />
                                        </label>



                                    </div>


                                    <div class="md:col-span-3">
                                        <label for="full_name" class="font-semibold">Client</label>
                                        <v-autocomplete
                                            clearable
                                            @update:search="onSearch"
                                            @keyup.enter="fetchCustomers"
                                            label="Search for a Customer and hit Enter"
                                            :items="customers"
                                            item-title="text"
                                            :loading="customersLoading"
                                            item-value="id"
                                            v-model="formData.client"
                                        ></v-autocomplete>


                                        <p v-if="formData.errors.client" class="text-sm text-red-500 font-semibold">{{formData.errors.client}}</p>
                                    </div>



                                    <div class="md:col-span-5 block" v-show="formData.type === 'Auto'">

                                        <label class="text-black font-semibold">
                                            Make
                                        </label>

                                        <select class="select w-full" v-model="formData.make">
                                            <option disabled selected class="text-black" value="">Select Make</option>
                                            <option v-for="(make, index) in props.makes" :key="index" :value="make">{{make}}</option>
                                        </select>

                                        <p v-if="formData.errors.make" class="text-sm text-red-500 font-semibold">{{formData.errors.make}}</p>


                                    </div>


                                    <div class="md:col-span-5 block" v-show="formData.type === 'Auto'">

                                        <label class="text-black font-semibold">
                                            Model
                                        </label>

                                        <select class="select w-full" v-model="formData.model">
                                            <option disabled selected class="text-black" value="">Select Model</option>
                                            <option v-for="(model, index) in props.models[formData.make]" :key="index" :value="model">{{model}}</option>

                                        </select>

                                        <p v-if="formData.errors.model" class="text-sm text-red-500 font-semibold">{{formData.errors.model}}</p>


                                    </div>


                                    <div class="md:col-span-5 block" v-show="formData.type === 'Auto'">

                                        <label class="text-black font-semibold">
                                            Production Year
                                        </label>

                                        <select class="select w-full" v-model="formData.prod_year">
                                            <option disabled selected class="text-black" value="">Select Year</option>
                                            <option v-for="(year, index) in range(1999,2024)" :key="index" :value="year">{{year}}</option>
                                        </select>

                                        <p v-if="formData.errors.prod_year" class="text-sm text-red-500 font-semibold">{{formData.errors.prod_year}}</p>


                                    </div>

                                    <div class="md:col-span-5 block" v-show="formData.type === 'Auto'">
                                        <label for="full_name" class="font-semibold">Plate Number</label>
                                        <input type="text" v-model="formData.pnum" id="full_name" class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"/>
                                        <p v-if="formData.errors.pnum" class="text-sm text-red-500 font-semibold">{{formData.errors.pnum}}</p>
                                    </div>


                                    <div class="md:col-span-5 block" v-show="formData.type === 'Auto'">
                                        <label for="full_name" class="font-semibold">Registeration Number</label>
                                        <input type="text" v-model="formData.regno" id="full_name" class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"/>
                                        <p v-if="formData.errors.regno" class="text-sm text-red-500 font-semibold">{{formData.errors.regno}}</p>
                                    </div>






                                    <div class="md:col-span-6">
                                        <label class="text-black font-semibold" for="textarea">Important Notes</label>
                                        <textarea id="textarea" v-model="formData.notes" type="textarea" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring"></textarea>
                                        <p v-if="formData.errors.notes" class="text-sm text-red-500 font-semibold">{{formData.errors.notes}}</p>

                                    </div>








                                    <div class="md:col-span-5 text-right">
                                        <div class="inline-flex items-end">
                                            <button class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded">Create Contract</button>
                                        </div>
                                    </div>


                                    <div class="md:col-span-5 text-right" >
                                        <div class="inline-flex items-end">
                                            <button type="reset" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded" @click="reset">Reset</button>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>



        <!-- Template end   -->

    </form>
</template>

<style scoped>

</style>
