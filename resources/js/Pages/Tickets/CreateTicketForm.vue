<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { computed, ref} from 'vue';
import { useToast } from "primevue/usetoast";

const toast = useToast();
const { props } = usePage();

const form = useForm({
    title: props.ticket?.title || '',
    description: props.ticket?.description || '',
    status: props.ticket?.status || 'open',
    priority: props.ticket?.priority || 'medium',
    created_at: props.ticket?.created_at || '',
    updated_at: props.ticket?.updated_at || '',
});

let isEdit = ref(false)
isEdit = computed(() => !!props.ticket);

const save = () => {
    if (isEdit.value) {
        form.put(route('tickets.update', props.ticket.id), {
            onSuccess: () =>
                // form.reset(),
                toast.add({ severity: 'success', summary: 'Success', detail: 'Ticket updated', life: 3000 }),

        });
    } else {
        form.post(route('tickets.store'), {
            onSuccess: () =>
                // form.reset(),
                toast.add({ severity: 'success', summary: 'Success', detail: 'Ticket saved', life: 3000 }),

        });
    }
};

const formatDate = (timestamp) => {
    if (!timestamp) return '';
    return new Date(timestamp).toLocaleString();
};

const createdDate = computed(() => formatDate(props.ticket.created_at));
const updatedDate = computed(() => formatDate(props.ticket.updated_at));

</script>

<template>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form @submit.prevent="save">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input v-model="form.title" id="title" type="text"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    placeholder="Enter the title" />
                <InputError :message="form.errors.title" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea v-model="form.description" id="description"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    rows="4" placeholder="Enter the description"></textarea>
                <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select v-model="form.status" id="status"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="closed">Closed</option>
                </select>
                <InputError :message="form.errors.status" class="mt-2" />
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                <select v-model="form.priority" id="priority"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <InputError :message="form.errors.priority" class="mt-2" />
            </div>

            <template v-if="isEdit">
                <div class="flex justify-between">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Created At</label>
                        <span class="text-gray-900">{{ createdDate }}</span>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Updated At</label>
                        <span class="text-gray-900">{{ updatedDate }}</span>
                    </div>
                </div>
            </template>

            <div class="flex justify-end gap-2">
                <Button label="Cancel" severity="secondary" class="mt-4" type="button" @click="form.reset(); form.clearErrors()" />
                <Button severity="primary" class="mt-4" type="submit">{{ isEdit ? 'Update Ticket' : 'Create Ticket' }}</Button>
            </div>

        </form>
    </div>
</template>
