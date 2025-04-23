<script>
// Components
import { useToast } from "primevue/usetoast";
import Skeleton from 'primevue/skeleton';

export default {
    inject: ['echo'],
    setup() {
        const toast = useToast();
        return { toast };
    },
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
        fetchWeather: {
            type: Function,
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
    },
    data: () => ({
        secondsSinceLastUpdate: 3600
    }),
    computed: {
        minutesSinceLastUpdate() {
            return Math.floor(this.secondsSinceLastUpdate / 60);
        }
    },
    mounted() {
        const weatherChannel = this.echo.channel('weather');
        
        weatherChannel.listen('.updating', (e) => {
            this.toast.add({ severity: 'info', summary: 'Weather', detail: 'Automatically updating...', life: 3000 });
        });

        weatherChannel.listen('.updated', (e) => {
            this.toast.add({ severity: 'success', summary: 'Weather', detail: 'Updated successfully!', life: 3000 });
        });
    },
    beforeUnmount() {
        this.echo.leave('weather');
    }
};
</script>

<template>
    <div class="col-span-12 xl:col-span-4">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Users</span>
                    <template v-if="isLoadingUsers">
                        <Skeleton width="2rem" height="1.5rem"></Skeleton>
                    </template>
                    <template v-else>
                        <div class="text-surface-900 dark:text-surface-0 font-medium text-xl">{{ users.length }}</div>
                    </template>
                </div>
                <div class="flex items-center justify-center bg-blue-100 dark:bg-blue-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-user text-blue-500 !text-xl"></i>
                </div>
            </div>
            <template v-if="isLoadingUsers">
                <Skeleton width="100%"></Skeleton>
            </template>
            <template v-else-if="!isLoadingUsers && failedLoadingUsers">
                <span class="text-primary font-medium">Unable to load users!</span>
            </template>
            <template v-else>
                <span class="text-primary font-medium">No new </span>
                <span class="text-muted-color">since last visit</span>
            </template>
        </div>
    </div>
    <div class="col-span-12 xl:col-span-4">
        <div class="card mb-0">
            <div class="flex justify-between mb-4">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Powered By</span>
                    <a class="text-surface-900 dark:text-surface-0 font-medium text-xl" href="https://openweathermap.org/" target="_blank">OpenWeather.org</a>
                </div>
                <div class="flex items-center justify-center bg-orange-100 dark:bg-orange-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-sun text-orange-500 !text-xl"></i>
                </div>
            </div>
            <template v-if="failedLoadingWeather">
                <span class="text-primary font-medium">Unable to load weather!</span>
            </template>
            <template v-else>
                <span class="text-primary font-medium">{{ minutesSinceLastUpdate }} minutes </span>
                <span class="text-muted-color">since last update</span>
            </template>
        </div>
    </div>

    <div class="col-span-12 xl:col-span-4">
        <div class="card mb-0">
            <div class="flex justify-between">
                <div>
                    <span class="block text-muted-color font-medium mb-4">Actions</span>
                    <Button
                        :disabled="isLoadingWeather"
                        class="mr-2 xl:mb-2 xl:mr-0"
                        :label="isLoadingWeather ? 'Refreshing Weather...' : 'Refresh Weather'"
                        icon="pi pi-refresh"
                        iconPos="right"
                        severity="warn"
                        @click="fetchWeather"
                    />
                </div>
                <div class="flex items-center justify-center bg-green-100 dark:bg-green-400/10 rounded-border" style="width: 2.5rem; height: 2.5rem">
                    <i class="pi pi-cog text-green-500 !text-xl"></i>
                </div>
            </div>
        </div>
    </div>
</template>
