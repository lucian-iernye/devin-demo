<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    form: Object,
    submitLabel: { type: String, default: 'Save' },
});

defineEmits(['submit']);
</script>

<template>
    <form @submit.prevent="$emit('submit')" class="space-y-6">
        <div>
            <InputLabel for="name" value="Name" />
            <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
            <InputError :message="form.errors.name" class="mt-2" />
        </div>

        <div>
            <InputLabel for="legal_name" value="Legal name" />
            <TextInput id="legal_name" v-model="form.legal_name" class="mt-1 block w-full" />
            <InputError :message="form.errors.legal_name" class="mt-2" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <InputLabel for="country" value="Country (2-letter ISO)" />
                <TextInput id="country" v-model="form.country" class="mt-1 block w-full" maxlength="2" required />
                <InputError :message="form.errors.country" class="mt-2" />
            </div>
            <div>
                <InputLabel for="default_commission_rate" value="Default commission rate (0–1)" />
                <TextInput
                    id="default_commission_rate"
                    v-model="form.default_commission_rate"
                    type="number"
                    step="0.0001"
                    min="0"
                    max="1"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.default_commission_rate" class="mt-2" />
            </div>
        </div>

        <div>
            <InputLabel for="status" value="Status" />
            <select
                id="status"
                v-model="form.status"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
                <option value="pending_kyc">Pending KYC</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>
            <InputError :message="form.errors.status" class="mt-2" />
        </div>

        <div class="flex items-center justify-end">
            <PrimaryButton :disabled="form.processing">{{ submitLabel }}</PrimaryButton>
        </div>
    </form>
</template>
