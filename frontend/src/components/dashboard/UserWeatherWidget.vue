<script>
import { getUsers } from '@/service/UserService';

export default {
    inject: ['backendHttpClient'],
    data: () => ({
        isLoadingUsers: false,
        users: []
    }),
    methods: {
        fetchUsers() {
            this.isLoadingUsers = true;
            getUsers(this.backendHttpClient)
                .then(response => {
                    this.users = response;
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.isLoadingUsers = false;
                });
        }
    },
    created() {
        this.fetchUsers();
    }
};
</script>

<template>
    <div class="col-span-12 card">
        <div class="font-semibold text-xl mb-4">Users</div>
        <DataTable :value="users" :rows="5" :paginator="true" responsiveLayout="scroll" :loading="isLoadingUsers">
            <Column field="name" header="Name" :sortable="true" style="width: 35%"></Column>
            <Column header="Location" :sortable="true" style="width: 35%">
                <template #body="slotProps">
                    {{ slotProps.data.latitude }} x {{ slotProps.data.longitude }}
                </template>
            </Column>
            <Column style="width: 15%" header="Weather">
                <template #body="slotProps">
                    70F
                </template>
            </Column>
            <Column style="width: 15%" header="View">
                <template #body>
                    <Button icon="pi pi-search" type="button" class="p-button-text"></Button>
                </template>
            </Column>
        </DataTable>
    </div>
</template>
