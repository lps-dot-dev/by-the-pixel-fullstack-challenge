<script>
// Dependencies
import moment from 'moment';

// Components
import { Button, Card, Column, DataTable, Dialog, Divider, Skeleton } from 'primevue';

export default {
    components: {
        Button,
        Card,
        Column,
        DataTable,
        Dialog,
        Divider,
        Skeleton
    },
    emits: {
        getNewUserPage: (newPageNumber) => {
            return Number.isInteger(newPageNumber);
        }
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
        userCount: {
            type: Number,
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
    },
    setup() {
        return { moment };
    },
    data: () => ({
        currentUser: null,
        currentWeather: null,
        showDetailedWeather: false
    }),
    methods: {
        /** @param {object} user */
        displayDetailedWeather(user) {
            const { id } = user;
            if (this.weather.has(id)) {
                this.currentUser = user;
                this.currentWeather = this.weather.get(id);
                this.showDetailedWeather = true;
            }
        },
        /** @param {import('primevue/datatable').DataTablePageEvent} event */
        handleNewPageRequest(event) {
            this.$emit('getNewUserPage', event.page + 1);
        }
    }
};
</script>

<template>
    <div class="col-span-12 card">
        <div class="font-semibold text-xl mb-4">User Weather</div>
        <DataTable
            :value="users"
            :loading="isLoadingUsers"
            :rows="5"
            :totalRecords="userCount"
            responsiveLayout="scroll"
            lazy
            paginator
            @page="handleNewPageRequest"
        >
            <Column field="name" header="Name" :sortable="true" style="width: 35%"></Column>
            <Column header="Location" :sortable="true" style="width: 35%">
                <template #body="slotProps">
                    {{ `${slotProps.data.city}, ${slotProps.data.state}` }}
                </template>
            </Column>
            <Column style="width: 15%" header="Weather">
                <template #body="slotProps">
                    <template v-if="isLoadingWeather">
                        <Skeleton width="2rem" height="1.5rem"></Skeleton>
                    </template>
                    <template v-else>
                        {{ weather.has(slotProps.data.id) ? `${Math.ceil(weather.get(slotProps.data.id).main.temp)}°F` : '70°F' }}
                    </template>
                </template>
            </Column>
            <Column style="width: 15%" header="View">
                <template #body="slotProps">
                    <Button :disabled="!weather.has(slotProps.data.id) || failedLoadingWeather || isLoadingWeather" icon="pi pi-search" type="button" class="p-button-text" @click="displayDetailedWeather(slotProps.data)"></Button>
                </template>
            </Column>
            <template #empty> No users found.</template>
        </DataTable>

        <Dialog v-model:visible="showDetailedWeather" :style="{ width: '450px' }" header="Weather Details" :modal="true">
            <div class="w-full bg-surface-0 dark:bg-surface-900 py-8 px-8 sm:px-20 flex flex-col items-center" style="border-radius: 53px">
                <span class="text-primary font-bold text-2xl">{{ currentUser?.city }}</span>
                <h1 class="text-surface-900 dark:text-surface-0 font-bold text-6xl lg:text-8xl mb-2">{{ Math.ceil(currentWeather?.main?.temp) }}°</h1>
                <div class="text-surface-600 dark:text-surface-200 mb-2">{{ currentWeather?.weather?.at(0)?.description }}</div>
                <div class="text-surface-400 dark:text-surface-400 mb-8">H:{{ Math.ceil(currentWeather?.main?.temp_max) }} L:{{ Math.ceil(currentWeather?.main?.temp_min) }}</div>
            </div>
            <div class="grid grid-cols-12 gap-8">
                <Card class="col-span-12 xl:col-span-6">
                    <template #title>Wind</template>
                    <template #content>
                        <div class="flex justify-between mt-2">
                            <strong>Wind</strong>
                            <small class="text-muted">{{ Math.ceil(currentWeather?.wind?.speed) }} mph</small>
                        </div>
                        <Divider />
                        <div class="flex justify-between">
                            <strong>Gust</strong>
                            <small class="text-muted">{{ Math.ceil(currentWeather?.wind?.gust) }} mph</small>
                        </div>
                        <Divider />
                        <div class="flex justify-between">
                            <strong>Direction</strong>
                            <small class="text-muted">{{ Math.ceil(currentWeather?.wind?.deg) }}°</small>
                        </div>
                    </template>
                </Card>
                <Card class="col-span-12 xl:col-span-6">
                    <template #content>
                        <div class="flex justify-between mt-2">
                            <strong>Sunrise</strong>
                            <small class="text-muted">{{ moment.unix(currentWeather?.sys?.sunrise).utc().format('hh:mm A') }}</small>
                        </div>
                        <Divider />
                        <div class="flex justify-between">
                            <strong>Sunset</strong>
                            <small class="text-muted">{{ moment.unix(currentWeather?.sys?.sunset).utc().format('hh:mm A') }}</small>
                        </div>
                    </template>
                </Card>
                <Card class="col-span-12 xl:col-span-4">
                    <template #title>Feels Like</template>
                    <template #content>
                        <div class="flex justify-start xl:justify-center mt-4">
                            <span class="text-surface-900 dark:text-surface-0 font-bold text-2xl lg:text-4xl">{{ Math.ceil(currentWeather?.main?.temp) }}°</span>
                        </div>
                    </template>
                </Card>
                <Card class="col-span-12 xl:col-span-4">
                    <template #title>Humidity</template>
                    <template #content>
                        <div class="flex justify-start xl:justify-center mt-4">
                            <span class="text-surface-900 dark:text-surface-0 font-bold text-2xl lg:text-4xl">{{ Math.ceil(currentWeather?.main?.humidity) }}%</span>
                        </div>
                    </template>
                </Card>
                <Card class="col-span-12 xl:col-span-4">
                    <template #title>Pressure</template>
                    <template #content>
                        <div class="flex justify-start xl:justify-center mt-4">
                            <span class="text-surface-900 dark:text-surface-0 font-bold text-2xl lg:text-4xl">{{ Math.ceil(currentWeather?.main?.pressure) }}</span>
                            <small class="text-muted">hPa</small>
                        </div>
                    </template>
                </Card> 
            </div>
        </Dialog>
    </div>
</template>
