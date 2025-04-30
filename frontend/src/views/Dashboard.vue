<script>
// Dependencies
import { storeToRefs } from "pinia";
import { useToast } from "primevue/usetoast";
import { useUserService } from '@/service/UserService';
import { useWeatherService } from "@/service/WeatherService";
import { useUserStore } from "@/stores/users";
import { useWeatherStore } from "@/stores/weather";

// Components
import NotificationsWidget from '@/components/dashboard/NotificationsWidget.vue';
import StatsWidget from '@/components/dashboard/StatsWidget.vue';
import UserWeatherWidget from '@/components/dashboard/UserWeatherWidget.vue';

export default {
    components: {
        StatsWidget,
        NotificationsWidget,
        UserWeatherWidget
    },
    inject: ['backendHttpClient', 'echo'],
    setup() {
        const { failedLoadingUsers, isLoadingUsers, getUsers } = useUserService();
        const { failedLoadingWeather, isLoadingWeather, updateWeather } = useWeatherService();

        const userStore = useUserStore();
        const { count: userCount, users } = storeToRefs(userStore);

        const weatherStore = useWeatherStore();
        const { weather } = storeToRefs(weatherStore);

        const toast = useToast();
        return { getUsers, failedLoadingUsers, failedLoadingWeather, isLoadingUsers, isLoadingWeather, toast, updateWeather, userCount, users, weather }
    },
    watch: {
        failedLoadingUsers: {
            immediate: true,
            handler(newFailureState) {
                if (newFailureState) {
                    this.toast.add({ severity: 'error', summary: 'Users', detail: 'Failed to load users!', life: 3000 });
                }
            }
        },
        failedLoadingWeather: {
            immediate: true,
            handler(newFailureState) {
                if (newFailureState) {
                    this.toast.add({ severity: 'error', summary: 'Weather', detail: 'Failed to update!', life: 3000 });
                }
            }
        }
    },
    methods: {
        /** @param {Number} pageNumber */
        fetchUsers(pageNumber) {
            console.log(pageNumber);
            this.getUsers(this.backendHttpClient, pageNumber);
        },
        fetchWeather() {
            this.updateWeather(this.backendHttpClient);
        }
    },
    created() {
        this.fetchUsers(0);
        this.fetchWeather();
    },
    mounted() {
        const weatherChannel = this.echo.channel('weather');
        
        weatherChannel.listen('.updating', (e) => {
            this.toast.add({ severity: 'info', summary: 'Weather', detail: 'Hold on a sec, updating...', life: 3000 });
        });

        weatherChannel.listen('.updated', (e) => {
            if ('weatherData' in e === false || Object.keys(e.weatherData).length === 0) {
                this.toast.add({ severity: 'warn', summary: 'Weather', detail: 'No updates to weather received!', life: 3000 });
            } else {
                this.toast.add({ severity: 'success', summary: 'Weather', detail: 'Updated successfully!', life: 3000 });
            }
        });
    },
    unmounted() {
        this.echo.leave('weather');
    }
};
</script>

<template>
    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 xl:col-span-8 grid grid-cols-12 gap-8">
            <StatsWidget
                :failed-loading-users="failedLoadingUsers"
                :failed-loading-weather="failedLoadingWeather"
                :fetch-weather="fetchWeather"
                :is-loading-users="isLoadingUsers"
                :is-loading-weather="isLoadingWeather"
                :user-count="userCount"
                :users="users"
                :weather="weather"
            />
            <UserWeatherWidget
                :failed-loading-users="failedLoadingUsers"
                :failed-loading-weather="failedLoadingWeather"
                :is-loading-users="isLoadingUsers"
                :is-loading-weather="isLoadingWeather"
                :user-count="userCount"
                :users="users"
                :weather="weather"
                @get-new-user-page="fetchUsers"
            />
        </div>
        <div class="col-span-12 xl:col-span-4">
            <NotificationsWidget />
        </div>
    </div>
</template>
