<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SupplierForm from '@/Pages/Suppliers/Partials/SupplierForm.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    legal_name: '',
    country: '',
    region: '',
    generation_mix: {},
    status: 'pending_kyc',
});

const submit = () => form.post(route('suppliers.store'));
</script>

<template>
    <Head title="New supplier" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">New supplier</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <SupplierForm :form="form" @submit="submit" submit-label="Create supplier" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
