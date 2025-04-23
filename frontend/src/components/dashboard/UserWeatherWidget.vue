<script>
// Dependencies
import { getUsers } from '@/service/UserService';
import { useWeatherService } from '@/service/WeatherService';

// Components
import Skeleton from 'primevue/skeleton';

export default {
    inject: ['backendHttpClient'],
    data: () => ({
        isLoadingUsers: false,
        users: [],
        weather: new Map()
    }),
    computed: {
        failedLoadingWeather() {
            return useWeatherService().errorOccurred.value;
        },
        isLoadingWeather() {
            return useWeatherService().isLoading.value;
        }
    },
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
        },
        fetchWeather() {
            useWeatherService()
                .getWeather(this.backendHttpClient)
                .then(response => {
                    this.weather = response;
                })
                .catch(error => {
                    console.error(error);
                });
        }
    },
    created() {
        this.fetchUsers();
        this.fetchWeather();
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
