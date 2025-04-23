<script>
// Dependencies
import { useUserService } from '@/service/UserService';
import { useWeatherService } from "@/service/WeatherService"

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
    inject: ['backendHttpClient'],
    setup() {
        const { failedLoadingUsers, isLoadingUsers, getUsers } = useUserService();
        const { failedLoadingWeather, isLoadingWeather, getWeather } = useWeatherService();

        return { failedLoadingUsers, failedLoadingWeather, isLoadingUsers, isLoadingWeather, getUsers, getWeather}
    },
    data: () => ({
        users: [],
        weather: new Map()
    }),
    methods: {
        fetchUsers() {
            this.getUsers(this.backendHttpClient)
                .then(response => {
                    this.users = response;
                })
                .catch(error => {
                    console.error(error);
                });
        },
        fetchWeather() {
            this.getWeather(this.backendHttpClient)
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
    <div class="grid grid-cols-12 gap-8">
        <div class="col-span-12 xl:col-span-8 grid grid-cols-12 gap-8">
            <StatsWidget
                :failed-loading-users="failedLoadingUsers"
                :failed-loading-weather="failedLoadingWeather"
                :fetch-weather="fetchWeather"
                :is-loading-users="isLoadingUsers"
                :is-loading-weather="isLoadingWeather"
                :users="users"
                :weather="weather"
            />
            <UserWeatherWidget
                :failed-loading-users="failedLoadingUsers"
                :failed-loading-weather="failedLoadingWeather"
                :is-loading-users="isLoadingUsers"
                :is-loading-weather="isLoadingWeather"
                :users="users"
                :weather="weather"
            />
        </div>
        <div class="col-span-12 xl:col-span-4">
            <NotificationsWidget />
        </div>
    </div>
</template>
