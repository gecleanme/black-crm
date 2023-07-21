<script setup xmlns="http://www.w3.org/1999/html">
import { ref } from "vue";

import { useForm } from "@inertiajs/vue3";

const formData = useForm({
    //create form and v-model the elements
    name: null,
    notes: null,
    sex: "Male",
    type: "Individual",
    vip: false,
    cell: null,
    ref: null,
    dob: null,
    secondary_phone: null,
    risk_level: 25,
    attachments: [],
});

const vip = ref(false);

const setVip = () => {
    vip.value = !vip.value;
    formData.vip = vip.value;
};

const packFiles = (event) => {
    //console.log(event.target.files)
    for (const att of event.target.files) {
        formData.attachments.push(att);
        //console.log(formData.attachments)
    }
};

const reset = () => formData.reset();

const sendData = () =>
    formData.post("/customer/store", {
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
    <Head title="Customer Create" />
    <form @submit.prevent="sendData">
        <!-- Template Start   -->
        <!-- component -->
        <!-- Alert    -->
        <div
            class="min-h-screen p-6 bg-gray-100 flex items-center justify-center"
        >
            <div class="container max-w-screen-lg mx-auto">
                <div>
                    <h2 class="font-semibold text-xl text-gray-600">
                        Add a Customer Profile
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
                                    Customer Details
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
                                            >Full Name</label
                                        >
                                        <input
                                            type="text"
                                            v-model="formData.name"
                                            id="full_name"
                                            class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"
                                        />
                                        <p
                                            v-if="formData.errors.name"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.name }}
                                        </p>
                                    </div>

                                    <div class="md:col-span-5">
                                        <label
                                            for="full_name"
                                            class="font-semibold"
                                            >Cell Phone</label
                                        >
                                        <input
                                            type="tel"
                                            v-model="formData.cell"
                                            id="full_name"
                                            class="h-10 outline-white border border-gray-300 mt-1 rounded px-4 w-full"
                                        />
                                        <p
                                            v-if="formData.errors.cell"
                                            class="text-sm text-red-500 font-semibold"
                                        >
                                            {{ formData.errors.cell }}
                                        </p>
                                    </div>

                                    <div
                                        class="md:col-span-2 block border-r-2 ml-2 px-2"
                                    >
                                        <label class="text-black font-semibold">
                                            Type
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text"
                                                >Individual</span
                                            >
                                            <input
                                                type="radio"
                                                v-model="formData.type"
                                                class="bg-gray-100 checked: radio"
                                                value="Individual"
                                                checked
                                            />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text"
                                                >Business</span
                                            >
                                            <input
                                                type="radio"
                                                v-model="formData.type"
                                                class="bg-gray-100 checked: radio"
                                                value="Business"
                                            />
                                        </label>
                                    </div>

                                    <div
                                        class="md:col-span-2 block"
                                        v-show="formData.type === 'Individual'"
                                    >
                                        <label class="text-black font-semibold">
                                            Sex
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text">Male</span>
                                            <input
                                                type="radio"
                                                v-model="formData.sex"
                                                class="bg-gray-100 checked: radio"
                                                value="Male"
                                                checked
                                            />
                                        </label>

                                        <label class="label cursor-pointer">
                                            <span class="label-text"
                                                >Female</span
                                            >
                                            <input
                                                type="radio"
                                                v-model="formData.sex"
                                                class="bg-gray-100 checked: radio"
                                                value="Female"
                                            />
                                        </label>
                                    </div>

                                    <div class="md:col-span-6">
                                        <label
                                            for="risk_range"
                                            class="font-semibold"
                                        >
                                            Risk Level
                                        </label>

                                        <input
                                            type="range"
                                            min="0"
                                            max="100"
                                            class="range"
                                            step="25"
                                            v-model="formData.risk_level"
                                        />
                                        <div
                                            class="w-full flex justify-between text-xs px-2"
                                        >
                                            <span>Very Low</span>
                                            <span>Low</span>
                                            <span>Moderate</span>
                                            <span>High</span>
                                            <span>Very High</span>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <div class="form-control">
                                            <label
                                                class="label cursor-pointer font-semibold"
                                            >
                                                <span class="label-text"
                                                    >VIP Client?</span
                                                >
                                                <input
                                                    type="checkbox"
                                                    :checked="formData.vip"
                                                    class="checkbox bg-gray-100"
                                                    @click="setVip()"
                                                />
                                            </label>
                                        </div>
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

                                    <div class="md:col-span-6">
                                        <label
                                            class="block text-sm font-medium text-black"
                                        >
                                            Attachments
                                        </label>

                                        <div class="flex text-sm text-gray-600">
                                            <label
                                                for="file-upload"
                                                class="p-2 mt-2 relative cursor-pointer bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500"
                                            >
                                                <span
                                                    class="text-center mx-auto"
                                                    >Upload a media file</span
                                                >
                                                <input
                                                    multiple
                                                    id="file-upload"
                                                    type="file"
                                                    @input="packFiles"
                                                    class="p-2 mt-2 sr-only"
                                                    accept=".jpg,.png,.mp4"
                                                />
                                            </label>
                                            <span
                                                class="text-sm text-black mt-4 ml-1"
                                                v-if="
                                                    formData.attachments.length
                                                "
                                            >
                                                {{
                                                    formData.attachments.length
                                                }}
                                                File(s) Uploaded!
                                            </span>
                                        </div>
                                        <p class="text-xs text-black m-2">
                                            PNG, JPG, MP4
                                        </p>
                                    </div>

                                    <div class="md:col-span-5 text-right">
                                        <div class="inline-flex items-end">
                                            <button
                                                class="bg-black hover:bg-gray-800 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Create Profile
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
</template>

<style scoped></style>
