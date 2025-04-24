<script>
// Dependencies
import { echo } from "@/service/EchoService";
import { useToast } from "primevue/usetoast";
import { useUserService } from '@/service/UserService';
import { useWeatherService } from "@/service/WeatherService"
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
        const { failedLoadingWeather, isLoadingWeather, updateWeather } = useWeatherService(echo);
        const { weather } = useWeatherStore();
        const toast = useToast();

        return { getUsers, failedLoadingUsers, failedLoadingWeather, isLoadingUsers, isLoadingWeather, toast, updateWeather, weather }
    },
    data: () => ({
        users: []
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
            this.updateWeather(this.backendHttpClient);
        }
    },
    created() {
        this.fetchUsers();
        this.fetchWeather();
    },
    mounted() {
        const weatherChannel = this.echo.channel('weather');
        
        weatherChannel.listen('.updating', (e) => {
            this.toast.add({ severity: 'info', summary: 'Weather', detail: 'Hold on a sec, updating...', life: 3000 });
        });

        weatherChannel.listen('.updated', (e) => {
            console.log(e);
            this.toast.add({ severity: 'success', summary: 'Weather', detail: 'Updated successfully!', life: 3000 });
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
