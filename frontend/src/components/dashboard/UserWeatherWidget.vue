<script>
// Components
import Skeleton from 'primevue/skeleton';

export default {
    components: {
        Skeleton
    },
    props: {
        failedLoadingUsers: {
            type: Boolean,
            required: true
        },
        failedLoadingWeather: {
            type: Boolean,
            required: true
        },
        isLoadingUsers: {
            type: Boolean,
            required: true
        },
        isLoadingWeather: {
            type: Boolean,
            required: true
        },
        users: {
            type: Array,
            required: true
        },
        weather: {
            type: Map,
            required: true
        }
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
                    <template v-if="isLoadingWeather">
                        <Skeleton width="2rem" height="1.5rem"></Skeleton>
                    </template>
                    <template v-else>
                        70F
                    </template>
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
