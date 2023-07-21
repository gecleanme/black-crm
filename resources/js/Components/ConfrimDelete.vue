<template>
    <button
        type="button"
        @click="setIsOpen(true)"
        style="background: red !important"
        class="inline-block rounded redbg px-3 pt-1 pb-1 text-xs font-medium uppercase leading-normal text-white shadow-[0_4px_9px_-4px_#3b71ca] transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] bg-black text-white hover:bg-gray-800"
    >
        <i class="material-icons">delete</i>
    </button>

    <!--
      Pass the `isOpen` ref to the `open` prop, and use the `close` event
      to set the ref back to `false` when the user clicks outside of
      the dialog or presses the escape key.
    -->
    <Dialog :open="isOpen" @close="setIsOpen">
        <DialogPanel
            class="fixed inset-0 flex justify-center items-center bg-gray-200 bg-opacity-50"
        >
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
                <DialogTitle class="text-lg font-bold mb-2"
                    >Delete Attachment?</DialogTitle
                >
                <DialogDescription class="text-sm text-gray-700 mb-4">
                </DialogDescription>

                <p class="text-center text-gray-700 mb-4">
                    Are you sure? Attachment will be permanently removed. This
                    action cannot be undone.
                </p>

                <div class="flex justify-center mt-4">
                    <!-- Cancel button -->
                    <button
                        class="bg-white hover:bg-gray-100 text-gray-700 font-bold py-2 px-4 border border-gray-400 rounded shadow mr-2"
                        @click="setIsOpen(false)"
                    >
                        Cancel
                    </button>

                    <!-- Delete button -->
                    <button
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow"
                        @click="handleDelete"
                    >
                        Delete
                    </button>
                </div>
            </div>
        </DialogPanel>
    </Dialog>
</template>

<script setup>
const props = defineProps({
    attachment: Object,
});

import { ref } from "vue";
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    DialogDescription,
} from "@headlessui/vue";

import { router, usePage } from "@inertiajs/vue3";

// The open/closed state lives outside of the Dialog and
// is managed by you.
const isOpen = ref(false);

function setIsOpen(value) {
    isOpen.value = value;
}

function handleDelete() {
    router.delete(`/del/attachments/${props.attachment.id}`, {
        onSuccess: () => {
            isOpen.value = false;
            //router.reload();
        },
    });
}
</script>
