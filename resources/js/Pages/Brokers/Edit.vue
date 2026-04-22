<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BrokerForm from '@/Pages/Brokers/Partials/BrokerForm.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    broker: Object,
});

const form = useForm({
    name: props.broker.name,
    legal_name: props.broker.legal_name ?? '',
    country: props.broker.country,
    default_commission_rate: props.broker.default_commission_rate,
    status: props.broker.status,
});

const submit = () => form.put(route('brokers.update', props.broker.id));
</script>

<template>
    <Head :title="`Edit ${broker.name}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit {{ broker.name }}
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <BrokerForm :form="form" @submit="submit" submit-label="Save changes" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
