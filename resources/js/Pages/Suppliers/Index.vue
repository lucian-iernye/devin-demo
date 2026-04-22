<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    suppliers: Object,
    filters: Object,
});

const q = ref(props.filters?.q ?? '');
const status = ref(props.filters?.status ?? '');

watch([q, status], () => {
    router.get(route('suppliers.index'), { q: q.value, status: status.value }, {
        preserveState: true,
        replace: true,
    });
});

const destroy = (supplier) => {
    if (!confirm(`Delete supplier "${supplier.name}"?`)) return;
    router.delete(route('suppliers.destroy', supplier.id));
};
</script>

<template>
    <Head title="Suppliers" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Suppliers</h2>
                <Link
                    :href="route('suppliers.create')"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500"
                >
                    New supplier
                </Link>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-4 flex gap-3">
                    <input
                        v-model="q"
                        type="text"
                        placeholder="Search name..."
                        class="w-full rounded-md border-gray-300 shadow-sm sm:w-64"
                    />
                    <select
                        v-model="status"
                        class="rounded-md border-gray-300 shadow-sm"
                    >
                        <option value="">All statuses</option>
                        <option value="pending_kyc">Pending KYC</option>
                        <option value="active">Active</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>

                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Country</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="supplier in suppliers.data" :key="supplier.id">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ supplier.name }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ supplier.country }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ supplier.status }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                    <Link
                                        :href="route('suppliers.edit', supplier.id)"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="destroy(supplier)"
                                        class="ml-4 text-red-600 hover:text-red-900"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="suppliers.data.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No suppliers yet.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
