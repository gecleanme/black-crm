<script setup>

import {ref, watch} from "vue";

const showItems= ref(false)

const props= defineProps({
    containerClasses: String,
    triggerClasses: String,
    listWrapperClasses: String
})

const closeFromOutside = (event) =>{
    if(!event.target.closest('.mindrop')){
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
    <div class="mindrop relative" :class="props.containerClasses">

    <div class="bg-black rounded px-2 py-3 text-white" @click="showItems=!showItems" :class="props.triggerClasses" >
        <slot name="trigger"></slot>
    </div>

    <div  class="bg-black absolute mt-2 px-3 py-2 rounded-md shadow-lg text-white z-50" v-show="showItems" :class="props.listWrapperClasses">
        <slot/>
      </div>
    </div>
</template>

<style scoped>


</style>
