<script setup>

import {ref, watch} from "vue";

const showItems= ref(false)

const closeFromOutside = (event) =>{
    if(!event.target.closest('.droppy')){
        showItems.value = false;
    }
}

watch(showItems, () =>{
    if (showItems.value){
        document.addEventListener('click', closeFromOutside)
    }
})

</script>
<template>
    <div class="droppy relative">

    <div @click="showItems=!showItems">
        <slot name="trigger"></slot>
    </div>

    <div  class="bg-black absolute mt-2 px-3 py-2 rounded-md shadow-lg text-white z-50" v-show="showItems">
        <slot/>
      </div>
    </div>
</template>

<style scoped>

.droppy {
    @apply w-[30%]
}


</style>
