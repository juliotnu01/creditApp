
<script setup>
import { ref } from "vue";

defineProps({
    title: String,
    data: {
        type: Array,
        required: true
    }
});
const isExpanded = ref(false)
</script>
<template>
    <div class="flex items-center justify-between pt-2 px-6">
        <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>
    </div>
    <div class=" bg-white p-6 rounded">
        <div v-for="(d, n) in data" :key="n">
            <h2 id="accordion-flush-heading-1 w-full">
                <button type="button"
                    class="flex justify-between items-center py-5 w-full font-medium text-left text-gray-900 rounded-t-xl border-b border-gray-200 "
                    @click="d.toggle = !d.toggle" v-show="d.show">
                    <span>{{ d.titulo }}</span>
                </button>
            </h2>
            <div v-show="d.toggle">
                <div class="py-5 border-b border-gray-200 ">
                    <slot :item="d" :name="'slot-' + n"></slot>
                </div>
            </div>
        </div>
    </div>
</template>