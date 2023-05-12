
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
        <div v-for="(d, n) in data" :key="n"
            :class="{ 'bg-white dark:bg-gray-900 text-gray-900 dark:text-white': isExpanded == true, 'text-gray-500 dark:text-gray-400': isExpanded == false }"
            @click="d.toggle = !d.toggle">
            <h2 id="accordion-flush-heading-1 w-full">
                <button type="button"
                    class="flex justify-between items-center py-5 w-full font-medium text-left text-gray-900 rounded-t-xl border-b border-gray-200 dark:border-gray-700 dark:text-white">
                    <span>{{ d.titulo }}</span>
                    <!-- <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg> -->
                </button>
            </h2>
            <div v-show="d.toggle">
                <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                    <slot :item="d" :name="'slot-' + n"></slot>
                </div>
            </div>
        </div>
    </div>
</template>