<script setup>
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { computed } from 'vue';
import { useToast } from "primevue/usetoast";
import { Inertia } from '@inertiajs/inertia'; // Import Inertia
import { usePermission } from '@/Composables/permissions.js';

const { hasPermission } = usePermission();
const canCreateSolutionFollowups = hasPermission('create solution followups');

const toast = useToast();

const ticketProps = defineProps({
    ticket: {
        type: Object,
        default: () => ({
            title: '',
            description: '',
            status: 'open',
            priority: 'medium',
            created_at: '',
            updated_at: '',
            followups: []
        })
    },
    mode: {
        type: String,
        default: 'create'
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

const followupForm = useForm({
    content: '',
    type: 'comment',
    ticket_id: ticketProps.ticket?.id || null,
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
        });
    } else if (isCreate.value) {
        form.post(route('tickets.store'), {
            onSuccess: () =>
                toast.add({ severity: 'success', summary: 'Success', detail: 'Ticket created', life: 3000 }),
        });
    }
};

const saveFollowUps = () => {
    followupForm.post(route('followups.store'), {
        onSuccess: (response) => {
            const message = response.props.flash.success || 'Follow-up added';
            toast.add({ severity: 'success', summary: 'Success', detail: message, life: 3000 });
            const newFollowup = response.props.ticket.followups[0];
            if (ticketProps.ticket) {
                ticketProps.ticket.followups.unshift(newFollowup);
            }
            followupForm.reset();
        },
        onError: (errors) => {
            const message = errors[0] || 'Error adding follow-up';
            toast.add({ severity: 'error', summary: 'Error', detail: message, life: 3000 });
        },
    });
};

const formatDate = (timestamp) => {
    if (!timestamp) return '';
    return new Date(timestamp).toLocaleString();
};

const createdDate = computed(() => formatDate(ticketProps.ticket?.created_at));
const updatedDate = computed(() => formatDate(ticketProps.ticket?.updated_at));

const goBack = () => {
    Inertia.visit(route('tickets.index')); // Replace with your route
};
</script>

<template>
    <Toast />

    <div class="max-w-2xl mx-auto p-4 sm:px-6 md:max-w-full md:space-x-2 lg:px-8">
        <div class="flex mb-10">
            <Button label="Back" severity="secondary" @click="goBack" text icon="pi pi-arrow-left" />
        </div>

        <div class="grid md:grid-cols-3 h-full gap-8">
            <form @submit.prevent="save" class="w-full min-h-full">
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

                <template v-if="isEdit || isShow">
                    <div class="flex justify-between">
                        <div class="mb-4">
                            <label class="block text-sm font-medium"
                                :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Created At</label>
                            <span :class="{ 'text-gray-500': isShow, 'text-gray-900': !isShow }">{{ createdDate
                                }}</span>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium"
                                :class="{ 'text-gray-500': isShow, 'text-gray-700': !isShow }">Updated At</label>
                            <span :class="{ 'text-gray-500': isShow, 'text-gray-900': !isShow }">{{ updatedDate
                                }}</span>
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

            <div class="mt-6" v-if="!isCreate">
                <h2 class="text-lg font-semibold mb-4">Followups</h2>
                <ul>
                    <ScrollPanel class="h-screen" style="width: 100%;" :dt="{
                        // bar: {
                        //     background: '{primary.color}'
                        // }
                    }">
                        <template v-if="ticketProps.ticket.followups.length > 0">
                            <li v-for="followup in ticketProps.ticket.followups" :key="followup.id" class="mb-4">
                                <div class="bg-white shadow rounded-lg p-4">
                                    <div class="flex justify-between">
                                        <div>
                                            <span class="font-medium">{{ followup.user.name }}</span>
                                            <span class="text-sm text-gray-500 ml-2">{{ formatDate(followup.created_at)
                                                }}</span>
                                        </div>
                                        <div>
                                            <span
                                                class="inline-block px-2 py-1 text-xs font-semibold text-white rounded-full"
                                                :class="{
                                                    'bg-amber-400': followup.type === 'comment',
                                                    'bg-blue-500': followup.type === 'solution'
                                                }">
                                                {{ followup.type }}
                                            </span>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-gray-700">{{ followup.content }}</p>
                                </div>
                            </li>
                        </template>
                        <template v-else>
                            <div class="bg-white shadow rounded-lg p-4 text-center">
                                <p class="text-gray-500">No followups</p>
                            </div>
                        </template>
                    </ScrollPanel>
                </ul>
            </div>

            <form @submit.prevent="saveFollowUps" class="w-full" v-if="isEdit">
                <div class="mb-4">
                    <label for="followup-content" class="block text-sm font-medium">Follow-up Content</label>
                    <textarea v-model="followupForm.content" id="followup-content" :class="[
                        'block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm'
                    ]" rows="4"></textarea>
                    <InputError :message="followupForm.errors.content" class="mt-2" />
                </div>

                <div class="mb-4">
                    <label for="followup-type" class="block text-sm font-medium">Follow-up Type</label>
                    <select
                    :disabled="!canCreateSolutionFollowups"
                    v-model="followupForm.type" id="followup-type" :class="[
                        'block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm'
                    ]">
                        <option value="comment">Comment</option>
                        <option value="solution">Solution</option>
                    </select>
                    <InputError :message="followupForm.errors.type" class="mt-2" />
                </div>

                <div class="flex justify-end gap-2">
                    <Button label="Reset" severity="secondary" class="mt-4" type="button"
                        @click="followupForm.reset(); followupForm.clearErrors()" />
                    <Button severity="primary" class="mt-4" type="submit">Add Follow-up</Button>
                </div>
            </form>
        </div>
    </div>
</template>
