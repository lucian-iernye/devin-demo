<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SupplierForm from '@/Pages/Suppliers/Partials/SupplierForm.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    supplier: Object,
});

const form = useForm({
    name: props.supplier.name,
    legal_name: props.supplier.legal_name ?? '',
    country: props.supplier.country,
    region: props.supplier.region ?? '',
    generation_mix: props.supplier.generation_mix ?? {},
    status: props.supplier.status,
});

const submit = () => form.put(route('suppliers.update', props.supplier.id));
</script>

<template>
    <Head :title="`Edit ${supplier.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit {{ supplier.name }}
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <SupplierForm :form="form" @submit="submit" submit-label="Save changes" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
