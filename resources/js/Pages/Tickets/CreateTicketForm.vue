<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { computed } from 'vue';
import { useToast } from "primevue/usetoast";

const toast = useToast();
// const { props } = usePage();

const ticketProps = defineProps({
    ticket: {
        type: Object,
        default: () => ({
            title: '',
            description: '',
            status: 'open',
            priority: 'medium',
            created_at: '',
            updated_at: ''
        })
    },
    mode: {
        type: String,
        default: 'create' // 'create', 'edit' or 'show'
    }
});

const form = useForm({
    title: ticketProps.ticket?.title || '',
    description: ticketProps.ticket?.description || '',
    status: ticketProps.ticket?.status || 'open',
    priority: ticketProps.ticket?.priority || 'medium',
    created_at: ticketProps.ticket?.created_at || '',
    updated_at: ticketProps.ticket?.updated_at || '',
});

const isEdit = computed(() => ticketProps.mode === 'edit');
const isCreate = computed(() => ticketProps.mode === 'create');
const isShow = computed(() => ticketProps.mode === 'show');
const isDisabled = computed(() => ticketProps.mode === 'show');

const save = () => {
    if (isEdit.value) {
        form.put(route('tickets.update', ticketProps.ticket.id), {
            onSuccess: () =>
                toast.add({ severity: 'success', summary: 'Success', detail: 'Ticket updated', life: 3000 }),
            // onError: () => {
            //     if (form.errors.message) {
            //         toast.add({ severity: 'error', summary: 'Error', detail: form.errors.message, life: 3000 });
            //     }
            // },
        });
    } else if (isCreate.value) {
        form.post(route('tickets.store'), {
            onSuccess: () =>
                toast.add({ severity: 'success', summary: 'Success', detail: 'Ticket created', life: 3000 }),
            // onError: () => {
            //     if (form.errors.message) {
            //         toast.add({ severity: 'error', summary: 'Error', detail: form.errors.message, life: 3000 });
            //     }
            // },
        });
    }
};

const formatDate = (timestamp) => {
    if (!timestamp) return '';
    return new Date(timestamp).toLocaleString();
};

const createdDate = computed(() => formatDate(ticketProps.ticket?.created_at));
const updatedDate = computed(() => formatDate(ticketProps.ticket?.updated_at));
</script>

<template>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form @submit.prevent="save">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium"
                    :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Title</label>
                <input v-model="form.title" id="title" type="text" :disabled="isDisabled" :class="[
                    'block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
                    { 'cursor-not-allowed text-gray-500 bg-gray-200': isDisabled }
                ]" />
                <InputError :message="form.errors.title" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium"
                    :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Description</label>
                <textarea v-model="form.description" id="description" :disabled="isDisabled" :class="[
                    'block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
                    { 'cursor-not-allowed text-gray-500 bg-gray-200': isDisabled }
                ]" rows="4"></textarea>
                <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium"
                    :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Status</label>
                <select v-model="form.status" id="status" :disabled="isDisabled" :class="[
                    'block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
                    { 'cursor-not-allowed text-gray-500 bg-gray-200': isDisabled }
                ]">
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed">Closed</option>
                </select>
                <InputError :message="form.errors.status" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium"
                    :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Priority</label>
                <select v-model="form.priority" id="priority" :disabled="isDisabled" :class="[
                    'block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm',
                    { 'cursor-not-allowed text-gray-500 bg-gray-200': isDisabled }
                ]">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <InputError :message="form.errors.priority" class="mt-2" />
            </div>

            <template v-if="ticketProps.ticket">
                <div class="flex justify-between">
                    <div class="mb-4">
                        <label class="block text-sm font-medium"
                            :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Created At</label>
                        <span :class="{ 'text-gray-500': isShow, 'text-gray-900': !isShow }">{{ createdDate }}</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium"
                            :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Updated At</label>
                        <span :class="{ 'text-gray-500': isShow, 'text-gray-900': !isShow }">{{ updatedDate }}</span>
                    </div>
                </div>
            </template>

            <div v-if="isEdit || isCreate" class="flex justify-end gap-2">
                <Button label="Cancel" severity="secondary" class="mt-4" type="button"
                    @click="form.reset(); form.clearErrors()" />
                <Button severity="primary" class="mt-4" type="submit">{{ isEdit ? 'Update Ticket' : 'Create Ticket'
                    }}</Button>
            </div>
        </form>
    </div>
</template>
