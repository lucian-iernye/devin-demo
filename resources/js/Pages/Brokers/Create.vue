<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BrokerForm from '@/Pages/Brokers/Partials/BrokerForm.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    legal_name: '',
    country: '',
    default_commission_rate: 0.02,
    status: 'pending_kyc',
});

const submit = () => form.post(route('brokers.store'));
</script>

<template>
    <Head title="New broker" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">New broker</h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <BrokerForm :form="form" @submit="submit" submit-label="Create broker" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
