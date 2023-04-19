<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const estadoCupoDisponible = ref([
    { text: 'Estado de evaluacion', value: 0, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito estado de propuesta', value: 1, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito en carta de aceptación', value: 2, color: 1 }, // 0: gris  1: verde 
    { text: 'Credito en contrato', value: 4, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito liberao', value: 5, color: 1 }, // 0: gris  1: verde 
    { text: 'Credito Rebotó', value: 6, color: 0 }, // 0: gris  1: verde 
    { text: 'Credito Pagado', value: 7, color: 0 }, // 0: gris  1: verde 
])

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl  mx-auto sm:px-6 lg:px-8">
                <div class="bg-white flex flex-row overflow-hidden shadow-xl sm:rounded-lg">
                    <!-- TU CUPO TOTAL DISPONIBLE -->
                    <div class="p-4 xl:w-1/4 md:w-11/6 w-full basis-1/2">
                        <div
                            class="h-full p-6 rounded-lg border-2 border-indigo-500 flex flex-col relative overflow-hidden">
                            <span
                                class="bg-indigo-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">SALDO
                                EN CRÉDITOS ACTIVOS</span>
                            <!-- <h2 class="text-sm tracking-widest title-font mb-1 font-medium">PRO</h2> -->
                            <h1
                                class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                                <span>$10,000.00</span>
                            </h1>
                            <p class="flex items-center text-gray-600 mb-2" v-for="(estado, n) in estadoCupoDisponible"
                                :key="n">
                                <span
                                    :class="{ 'w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0': !estado.color, 'w-4 h-4 mr-2 inline-flex items-center justify-center bg-green-400 text-white rounded-full flex-shrink-0': estado.color }">
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2.5" class="w-3 h-3" viewBox="0 0 24 24">
                                        <path d="M20 6L9 17l-5-5"></path>
                                    </svg>
                                </span>{{ estado.text }}
                            </p>
                            <div class="flex gap-2 my-2">
                                <button
                                    class="flex items-center mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Recaudos
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center  mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Historial de Creditos
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                
                            </div>
                            <p class="text-xs text-gray-500 mt-3">credito: ab467c0f-99ad-4fab-b2b9-450304c49429</p>
                        </div>
                    </div>
                    <!-- SALDO EN CRÉDITOS ACTIVOS -->
                    <div class="p-4 xl:w-1/4 md:w-11/6 w-full basis-1/2">
                        <div
                            class="h-full p-6 rounded-lg border-2 border-indigo-500 flex flex-col relative overflow-hidden">
                            <span
                                class="bg-indigo-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">
                                TU CUPO TOTAL DISPONIBLE</span>
                            <!-- <h2 class="text-sm tracking-widest title-font mb-1 font-medium">PRO</h2> -->
                            <h1
                                class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                                <span>$7937,473.53</span>
                            </h1>

                            <div class="flex gap-2 my-2 mt-[14.5rem]">
                                <button
                                    class="flex items-center mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Solicitar Aumento
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                                <button
                                    class="flex items-center mt-auto text-white bg-indigo-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-indigo-600 rounded">
                                    Transferir Cupo
                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </AppLayout>
</template>
