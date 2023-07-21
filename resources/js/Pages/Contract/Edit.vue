<script setup xmlns="http://www.w3.org/1999/html">
import { computed, inject, onMounted, ref } from "vue";

import { useForm, Link } from "@inertiajs/vue3";
import "@dafcoe/vue-collapsible-panel/dist/vue-collapsible-panel.css";
import FlashSuccess from "@/Components/FlashSuccess.vue";

const props = defineProps({
    makes: Array,
    models: Array,
    contract: Object,
});

const formData = useForm({
    //create form and v-model the elements
    title: props.contract.title,
    make: props.contract.make ?? "",
    client_id: props.contract.client_id ?? "",
    notes: props.contract.notes ?? null,
    prod_year: props.contract.prod_year ?? "",
    type: props.contract.type ?? "Auto",
    model: props.contract.model ?? "",
    regno: props.contract.regno ?? null,
    pnum: props.contract.pnum ?? null,
    lic_exp: props.contract.lic_exp ?? null,
});

const currentDate = new Date();
const currentYear = currentDate.getFullYear();

const customers = ref([]);
const searchQuery = ref("");
const customersLoading = ref(false);
const customersComputed = computed(() => {
    return customers.value.concat(props.contract.customer);
});
function fetchCustomers() {
    customersLoading.value = true;
    axios
        .get(`/customers?search=${searchQuery.value}`)
        .then(({ data }) => {
            customers.value = data.data;
        })
        .finally(() => {
            customersLoading.value = false;
        });
}

function onSearch(q) {
    searchQuery.value = q;
}

function range(start, end) {
    return Array.from({ length: end - start + 1 }, (_, i) => start + i);
}

const reset = () => formData.reset();

const sendData = () =>
    formData.put(`/contract/update/${props.contract.id}`, {
        onSuccess: () => formData.reset(),
    });
</script>

<script>
import Layout from "@/Layouts/Layout.vue";
import { Head } from "@inertiajs/vue3";

export default {
    layout: Layout,
};
</script>

<template>
    <FlashSuccess />

    <Head title="Contract Edit" />
    <form @submit.prevent="sendData">
        <!-- Template Start   -->
        <!-- component -->
        <!-- Alert    -->
        <div
            class="min-h-screen p-6 bg-gray-100 flex items-center justify-center"
            v-if="!formData.wasSuccessful"
        >
            <div class="container max-w-screen-lg mx-auto">
                <div>
                    <h2 class="font-semibold text-xl text-gray-600">
                        Edit a Contract
                    </h2>
                    <p class="text-gray-500 mb-6">
                        Required fields are marked with
                        <span class="text-sm text-red-500">* </span>
                    </p>

                    <div
                        class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6"
                    >
                        <div
                            class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3"
                        >
                            <div class="text-gray-600">
                                <p class="font-medium text-lg">
                                    Contract Details
                                </p>
                            </div>

                            <div class="lg:col-span-2">
                                <div
                                    class="grid gap-4 gap-y-2 text-sm grid-cols-1 md:grid-cols-5 rounded-md border-white outline-white"
                                >
                                    <div class="md:col-span-5">
                                        <label
                                            for="full_name"
                                            class="font-semibold"
                                            >Title</label
                                        >
                                        <input
                                            type="text"
                                            v-model="formData.title"
                                            id="full_name"
                                            class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"
                                        />
                                        <p
                                            v-if="formData.errors.title"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.title }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-2 block border-r-2 ml-2 px-2"
                                    >
                                        <label class="text-black font-semibold">
                                            Insurance Type
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text">Auto</span>
                                            <input
                                                type="radio"
                                                v-model="formData.type"
                                                class="bg-gray-100 checked: radio"
                                                value="Auto"
                                            />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text"
                                                >Travel</span
                                            >
                                            <input
                                                type="radio"
                                                v-model="formData.type"
                                                class="bg-gray-100 checked: radio"
                                                value="Travel"
                                            />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text"
                                                >Health</span
                                            >
                                            <input
                                                type="radio"
                                                v-model="formData.type"
                                                class="bg-gray-100 checked: radio"
                                                value="Health"
                                            />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text"
                                                >Naval</span
                                            >
                                            <input
                                                type="radio"
                                                v-model="formData.type"
                                                class="bg-gray-100 checked: radio"
                                                value="Naval"
                                            />
                                        </label>
                                    </div>

                                    <div class="md:col-span-3">
                                        <label
                                            for="full_name"
                                            class="font-semibold"
                                            >Client</label
                                        >
                                        <v-autocomplete
                                            clearable
                                            @update:search="onSearch"
                                            @keyup.enter="fetchCustomers"
                                            label="Search for a Customer and hit Enter"
                                            :items="customersComputed"
                                            item-title="name"
                                            :loading="customersLoading"
                                            item-value="id"
                                            v-model="formData.client_id"
                                        ></v-autocomplete>

                                        <p
                                            v-if="formData.errors.client_id"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.client_id }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-5 block"
                                        v-show="formData.type === 'Auto'"
                                    >
                                        <label class="text-black font-semibold">
                                            Make
                                        </label>

                                        <select
                                            class="select w-full"
                                            v-model="formData.make"
                                        >
                                            <option
                                                disabled
                                                selected
                                                class="text-black"
                                                value=""
                                            >
                                                Select Make
                                            </option>
                                            <option
                                                v-for="(
                                                    make, index
                                                ) in props.makes"
                                                :key="index"
                                                :value="make"
                                            >
                                                {{ make }}
                                            </option>
                                        </select>

                                        <p
                                            v-if="formData.errors.make"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.make }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-5 block"
                                        v-show="formData.type === 'Auto'"
                                    >
                                        <label class="text-black font-semibold">
                                            Model
                                        </label>

                                        <select
                                            class="select w-full"
                                            v-model="formData.model"
                                        >
                                            <option
                                                disabled
                                                selected
                                                class="text-black"
                                                value=""
                                            >
                                                Select Model
                                            </option>
                                            <option
                                                v-for="(model, index) in props
                                                    .models[formData.make]"
                                                :key="index"
                                                :value="model"
                                            >
                                                {{ model }}
                                            </option>
                                        </select>

                                        <p
                                            v-if="formData.errors.model"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.model }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-5 block"
                                        v-show="formData.type === 'Auto'"
                                    >
                                        <label class="text-black font-semibold">
                                            Production Year
                                        </label>

                                        <select
                                            class="select w-full"
                                            v-model="formData.prod_year"
                                        >
                                            <option
                                                disabled
                                                selected
                                                class="text-black"
                                                value=""
                                            >
                                                Select Year
                                            </option>
                                            <option
                                                v-for="(year, index) in range(
                                                    currentYear - 15,
                                                    currentYear + 1,
                                                )"
                                                :key="index"
                                                :value="year"
                                            >
                                                {{ year }}
                                            </option>
                                        </select>

                                        <p
                                            v-if="formData.errors.prod_year"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.prod_year }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-5 block"
                                        v-show="formData.type === 'Auto'"
                                    >
                                        <label
                                            for="full_name"
                                            class="font-semibold"
                                            >Plate Number</label
                                        >
                                        <input
                                            type="text"
                                            v-model="formData.pnum"
                                            id="full_name"
                                            class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"
                                        />
                                        <p
                                            v-if="formData.errors.pnum"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.pnum }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-5 block"
                                        v-show="formData.type === 'Auto'"
                                    >
                                        <label
                                            for="full_name"
                                            class="font-semibold"
                                            >Registeration Number</label
                                        >
                                        <input
                                            type="text"
                                            v-model="formData.regno"
                                            id="full_name"
                                            class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"
                                        />
                                        <p
                                            v-if="formData.errors.regno"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.regno }}
                                        </p>
                                    </div>

                                    <div class="md:col-span-6">
                                        <label
                                            class="text-black font-semibold"
                                            for="textarea"
                                            >Important Notes</label
                                        >
                                        <textarea
                                            id="textarea"
                                            v-model="formData.notes"
                                            type="textarea"
                                            class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:outline-none focus:ring"
                                        ></textarea>
                                        <p
                                            v-if="formData.errors.notes"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.notes }}
                                        </p>
                                    </div>

                                    <!--               Cycle loop start                     -->

                                    <div class="md:col-span-6">
                                        <label
                                            class="text-black font-semibold"
                                            for="cycle_loop"
                                            >Cycles ({{
                                                contract.cycles.length
                                            }})</label
                                        >

                                        <vue-collapsible-panel-group
                                            v-for="cycle in contract.cycles"
                                            :key="cycle.id"
                                            id="cycle_loop"
                                            class="mt-2"
                                        >
                                            <vue-collapsible-panel
                                                :expanded="false"
                                            >
                                                <template #title>
                                                    <span
                                                        >Starts:
                                                        {{
                                                            cycle.start_date
                                                        }}</span
                                                    >
                                                </template>

                                                <template #content>
                                                    <div class="py-4 px-4">
                                                        <div class="spacing">
                                                            <b> Ends</b>:
                                                            {{ cycle.end_date }}
                                                        </div>
                                                        <hr />

                                                        <div class="spacing">
                                                            <b> Value</b>:
                                                            {{ cycle.value }}
                                                        </div>

                                                        <hr />
                                                        <div class="spacing">
                                                            <b>Premium </b>:
                                                            {{ cycle.premium }}
                                                        </div>
                                                        <hr />
                                                        <div class="spacing">
                                                            <b>Vendor</b>:
                                                            {{ cycle.vendor }}
                                                        </div>
                                                        <hr />
                                                        <div class="spacing">
                                                            <b>Notes</b>:
                                                            {{ cycle.notes }}
                                                        </div>

                                                        <hr />
                                                        <div class="spacing">
                                                            <b
                                                                >Attachments ({{
                                                                    cycle
                                                                        .attachment_urls
                                                                        .length
                                                                }})</b
                                                            >:

                                                            <div
                                                                v-for="(
                                                                    url, index
                                                                ) in cycle.attachment_urls"
                                                                :key="index"
                                                                class="mt-2"
                                                            >
                                                                <a
                                                                    :href="url"
                                                                    target="_blank"
                                                                    class="underline py-4"
                                                                    >Preview</a
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <hr />

                                                    <div
                                                        class="spacing flex justify-evenly"
                                                    >
                                                        <b>Actions</b
                                                        ><Link
                                                            :href="`/cycle/edit/${cycle.id}`"
                                                            >Edit</Link
                                                        >
                                                    </div>
                                                </template>
                                            </vue-collapsible-panel>
                                        </vue-collapsible-panel-group>
                                    </div>

                                    <!--               Cycle loop end                     -->

                                    <div class="md:col-span-5 text-right mb-10">
                                        <div class="inline-flex items-end">
                                            <Link
                                                class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded"
                                                as="button"
                                                :href="`/cycle/create/${contract.id}`"
                                                >Add new Cycle</Link
                                            >
                                        </div>
                                    </div>

                                    <hr style="border: 2px solid gray" />

                                    <div class="md:col-span-5 text-right">
                                        <div class="inline-flex items-end">
                                            <button
                                                class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Update Contract
                                            </button>
                                        </div>
                                    </div>

                                    <div class="md:col-span-5 text-right">
                                        <div class="inline-flex items-end">
                                            <button
                                                type="reset"
                                                class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded"
                                                @click="reset"
                                            >
                                                Reset
                                            </button>
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
    <!--
    <div class="p-6 bg-gray-100 flex items-center justify-center">
        <div class="container max-w-screen-lg mx-auto">
            <div>
                <h2 class="font-semibold text-xl text-gray-600">Add a new Contract Cycle</h2>
                <p class="text-gray-500 mb-6">Required fields are marked with <span class="text-sm text-red-500">* </span></p>

                <div class="bg-white rounded shadow-lg p-4 px-4 md:p-8 mb-6">
                    <div class="grid gap-4 gap-y-2 text-sm grid-cols-1 lg:grid-cols-3">
                        <div class="text-gray-600">
                            <p class="font-medium text-lg">Cycle Details</p>
                        </div>

                        <div class="md:col-span-6 mt-4">

                            <div class="flex flex-col w-full lg:flex-row my-2">

                            </div>

                            <div class="flex text-sm text-gray-600">



                            </div>
                            <p class="text-xs text-black m-2">
                            </p>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    -->
</template>

<style scoped>
.spacing {
    @apply px-2 py-2;
}
</style>
