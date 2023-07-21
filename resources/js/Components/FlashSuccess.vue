<script setup>
import $swal from "sweetalert2";
import { usePage } from "@inertiajs/vue3";

if (usePage().props.flash.success) {
    const Toast = $swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", $swal.stopTimer);
            toast.addEventListener("mouseleave", $swal.resumeTimer);
        },
    });

    Toast.fire({
        icon: "success",
        title: "Operation Succeeded",
    });
}
</script>

<style scoped></style>
