<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
const props = defineProps({
    isCliente: Boolean,
    user_id: Number,
});
</script>
<template>
    <AppLayout title="Crear Creditos" :isCliente="isCliente">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Creditos
            </h2>
        </template>
        <div class="grid grid-cols-12 gap-2" >
            <div class="col-span-8">
                <div>
                    
                </div>
            </div>
            <div class="bg-green-500 col-span-4">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Error, inventore fuga. Distinctio, qui impedit et tempora amet sint aliquam, ipsa recusandae sit excepturi blanditiis in error facilis quas quae praesentium?
            </div>
        </div>
    </AppLayout>
</template>