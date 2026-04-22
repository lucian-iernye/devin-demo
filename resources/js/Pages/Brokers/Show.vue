<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    broker: Object,
});
</script>

<template>
    <Head :title="broker.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ broker.name }}</h2>
                <Link
                    :href="route('brokers.edit', broker.id)"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                >
                    Edit
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-3xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Legal name</dt>
                            <dd>{{ broker.legal_name || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Country</dt>
                            <dd>{{ broker.country }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Default commission rate</dt>
                            <dd>{{ broker.default_commission_rate }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Status</dt>
                            <dd>{{ broker.status }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
