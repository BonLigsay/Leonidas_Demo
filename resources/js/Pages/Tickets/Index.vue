<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, Link, usePage } from '@inertiajs/vue3';
import CreateTicketForm from './CreateTicketForm.vue';

import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";

const confirm = useConfirm();
const toast = useToast();

const { props } = usePage();
const { flash } = props;

defineProps(['tickets']);

const form = useForm({
    message: '',
});

const deleteConfirm = (id) => {
    confirm.require({
        message: 'Are you sure you want to proceed?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Confirm'
        },
        accept: () => {
            form.delete(route("tickets.destroy", { id: id }), {
                preserveScroll: true,
                onSuccess: () => toast.add({ severity: 'success', summary: 'Success', detail: 'Ticket deleted', life: 3000 }),
            });
        },
    });
};

const navigateToCreate = () => {
    route('tickets.create');
};

// const showToast = () =>  toast.add({ severity: 'success', summary: 'Success', detail: 'wqwqewqewqe', life: 3000 });
// // Show toast message if there's a success message
// if (flash) {
//     showToast()
// }
</script>

<template>
    <Toast />
    <ConfirmDialog></ConfirmDialog>

    <Head title="Tickets" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tickets</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <DataTable :value="tickets" stripedRows paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                        tableStyle="min-width: 50rem"
                        paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        currentPageReportTemplate="{first} to {last} of {totalRecords}">

                        <template #header>
                            <div class="flex flex-wrap items-center justify-end gap-2">
                                <Link :href="route('tickets.create')">
                                    <Button label="Add Ticket" icon="pi pi-plus-circle" rounded link />
                                </Link>
                            </div>
                        </template>

                        <Column field="id" header="#"></Column>
                        <Column field="title" header="Title"></Column>
                        <Column field="description" header="Description"></Column>
                        <Column field="status" header="Status"></Column>
                        <Column field="priority" header="Priority"></Column>

                        <Column header="Actions">
                            <template #body="slotProps">
                                <div class="flex">
                                    <Link :href="route('tickets.show', slotProps.data.id)">
                                        <Button label="" icon="pi pi-eye" text severity="contrast" />
                                    </Link>

                                    <Link :href="route('tickets.edit', slotProps.data.id)">
                                        <Button label="" icon="pi pi-pencil" text severity="contrast" />
                                    </Link>

                                    <Button
                                        label=""
                                        @click="() => deleteConfirm(slotProps.data.id)"
                                        icon="pi pi-trash" text
                                        severity="contrast" />
                                </div>
                            </template>
                        </Column>
                    </DataTable>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
