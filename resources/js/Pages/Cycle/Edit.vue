<script setup xmlns="http://www.w3.org/1999/html">

import {computed, ref} from "vue";

import {Link, router, useForm} from "@inertiajs/vue3";
import ConfrimDelete from "@/Components/ConfrimDelete.vue";
import FlashSuccess from "@/Components/FlashSuccess.vue";

const props = defineProps({
    cycle: Object,
    url: String
});

let formattedStart;
const startDate = ref(new Date());

formattedStart = computed(() => {
    return useFormatDateToISO(startDate.value);
});

let formattedEnd;
const endDate = ref(new Date());

formattedEnd = computed(() => {
    return useFormatDateToISO(endDate.value);
});

function useFormatDateToISO(dateToFormat) {
    dateToFormat = new Date(new Date(dateToFormat).toDateString());

    let day = String(dateToFormat.getDate()).padStart(2, '0');
    let month = String(dateToFormat.getMonth() + 1).padStart(2, '0');
    let year = dateToFormat.getFullYear();

    return `${year}-${month}-${day}`;
}


const formData = useForm({
    // create form and v-model the elements
    _method: 'put',
    value: props.cycle.value ?? null,
    premium: props.cycle.premium ?? null,
    start_date: useFormatDateToISO(props.cycle.start_date),
    end_date: useFormatDateToISO(props.cycle.end_date),
    vendor:props.cycle.vendor ?? "",
    notes: props.cycle.notes ?? "",
    attachments : []
});
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


const packFiles = (event) => {
        //console.log(event.target.files)
    for (const att of event.target.files) {
        formData.attachments.push(att);
        //console.log(formData.attachments)
    }
};




const reset = () => formData.reset();

const sendData = () => formData.transform((data) => ({
    ...data,
    start_date:useFormatDateToISO(data.start_date),
    end_date:useFormatDateToISO(data.end_date),
})).post(`/cycle/update/${props.cycle.id}`,{
    //onSuccess: () => router.visit(`/cycle/edit/${props.cycle.id}`)
});


</script>

<script>
import Layout from "@/Layouts/Layout.vue";
import {Head} from "@inertiajs/vue3";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
//import {ref} from "vue";

//const date = ref();
export default {
    layout: Layout,
    name:"CycleEdit"
}
</script>

<template>
    <FlashSuccess/>
    <Head title="Contract Cycle Edit" />
    <form @submit.prevent="sendData">

        <!-- Template Start   -->
        <!-- component -->
        <!-- Alert    -->
        <div class="min-h-screen p-6 bg-gray-100 flex items-center justify-center">
            <div class="container max-w-screen-lg mx-auto">
                <div>
                    <h2 class="font-semibold text-xl text-gray-600">Edit a Contract Cycle</h2>
                    <p class="text-gray-500 mb-6">Required fields are marked with <span class="text-sm text-red-500">* </span></p>

                    <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                        <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                            <div class="text-gray-600">
                                <p class="font-medium text-lg">Cycle Details</p>
                            </div>

                            <div class="lg:col-span-2">
                                <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5 rounded-md border-white outline-white">


                                    <div class="md:col-span-5">
                                        <label for="full_name" class="font-semibold">Value</label>
                                        <input type="number" v-model="formData.value" id="full_name" class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"/>
                                        <p v-if="formData.errors.value" class="text-sm text-red-500 font-semibold">{{formData.errors.value}}</p>
                                    </div>


                                    <div class="md:col-span-5">
                                        <label for="full_name" class="font-semibold">Premium</label>
                                        <input type="number" v-model="formData.premium" id="full_name" class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"/>
                                        <p v-if="formData.errors.premium" class="text-sm text-red-500 font-semibold">{{formData.errors.premium}}</p>
                                    </div>



                                    <div class="md:col-span-3">
                                        <label for="full_name" class="font-semibold">Vendor</label>

                                        <select class="select w-full" v-model="formData.vendor">
                                            <option disabled selected class="text-black" value="">Select Vendor</option>
                                            <option value="Homer">Homer</option>
                                        </select>

                                        <p v-if="formData.errors.vendor" class="text-sm text-red-500 font-semibold">{{formData.errors.vendor}}</p>
                                    </div>


                                    <div class="md:col-span-5">
                                        <p class="font-semibold">Start Date</p>

                                        <VueDatePicker v-model="formData.start_date" :enable-time-picker="false"
                                        ></VueDatePicker>

                                        <p v-if="formData.errors.start_date" class="text-sm text-red-500 font-semibold">{{formData.errors.start_date}}</p>

                                    </div>

                                    <div class="md:col-span-5">
                                        <p class="font-semibold">End Date</p>

                                        <VueDatePicker v-model="formData.end_date" :enable-time-picker="false"
                                        ></VueDatePicker>

                                        <p v-if="formData.errors.end_date" class="text-sm text-red-500 font-semibold">{{formData.errors.end_date}}</p>

                                    </div>


                                    <div class="md:col-span-6">
                                        <label class="text-black font-semibold" for="textarea">Important Notes</label>
                                        <textarea id="textarea" v-model="formData.notes" type="textarea" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring"></textarea>
                                        <p v-if="formData.errors.notes" class="text-sm text-red-500 font-semibold">{{formData.errors.notes}}</p>

                                    </div>

                                    <div class="md:col-span-6">
                                        <label class="block text-sm font-medium text-black">
                                            Attachments ({{cycle.attachments.length}})
                                        </label>

                                        <div class="flex flex-col w-full lg:flex-row my-2" v-for="attachment in cycle.attachments">
                                            <div class="grid flex-grow h-32 card bg-base-300 rounded-box place-items-center"><a  target="_blank" :href="`${props.url}/storage/${attachment.attachments}`" class="btn btn-ghost" as="button">Preview</a>
                                            </div>

                                            <ConfrimDelete :attachment="attachment"/>

                                        </div>

                                        <div class="flex text-sm text-gray-600">
                                            <label for="file-upload" class="p-2 mt-2 relative cursor-pointer bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span class="text-center mx-auto">Upload a media file</span>
                                                <input multiple id="file-upload" type="file" @input="packFiles" class="p-2 mt-2 sr-only" accept=".jpg,.png,.mp4"/>

                                            </label>
                                            <span class="text-sm text-black mt-4 ml-1" v-if="formData.attachments.length">
                                               {{formData.attachments.length}} File(s) Uploaded!
                                            </span>





                                        </div>
                                        <p class="text-xs text-black m-2">
                                            PNG, JPG, MP4
                                        </p>



                                    </div>
                                </div>

                                <div class="md:col-span-5 float-right">
                                        <div class="inline-flex items-end">
                                            <button :disabled="formData.processing" class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded disabled:bg-opacity-40">Update Cycle</button>
                                        </div>
                                    </div>


                                    <div class="md:col-span-5 mx-1 float-right">
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
        <!-- Template end   -->

    </form>

</template>

<style scoped>

</style>
