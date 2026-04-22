<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    supplier: Object,
});
</script>

<template>
    <Head :title="supplier.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ supplier.name }}</h2>
                <Link
                    :href="route('suppliers.edit', supplier.id)"
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
                            <dd>{{ supplier.legal_name || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Country</dt>
                            <dd>{{ supplier.country }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Region</dt>
                            <dd>{{ supplier.region || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500">Status</dt>
                            <dd>{{ supplier.status }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h3 class="mb-3 text-lg font-medium">Tariffs</h3>
                    <ul v-if="supplier.tariffs?.length" class="divide-y">
                        <li v-for="t in supplier.tariffs" :key="t.id" class="py-2 text-sm">
                            {{ t.name }} — {{ t.price_per_kwh }} {{ t.currency }}/kWh
                            ({{ t.contract_length_months }} mo, {{ t.green_percentage }}% green)
                        </li>
                    </ul>
                    <p v-else class="text-sm text-gray-500">No tariffs yet.</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
