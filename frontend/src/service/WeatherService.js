import { echo as echoInstance } from "@/service/EchoService";
import { onMounted, onUnmounted, ref } from "vue";
import { useWeatherStore } from "../stores/weather";

export function useWeatherService() {
    /** @type {import("vue").Ref<import('laravel-echo').default>} */
    const echo = ref(echoInstance);
    const failedLoadingWeather = ref(false);
    const isLoadingWeather = ref(false);
    const weatherStore = useWeatherStore();

    /**
     * Makes a HTTP request to initiate the update weather process. This HTTP call does not respond with updated weather data.
     * The updated weather data will be broadcasted via websockets once it is ready.
     * 
     * @param {import('axios').Axios} httpClient 
     * @returns {Promise<true|Error>}
     */
    async function updateWeather(httpClient) {
        failedLoadingWeather.value = false;
        isLoadingWeather.value = true;
        
        try {
            await httpClient.get('/weather');
        } catch (error) {
            failedLoadingWeather.value = true;
            // Prevent users from trying again so soon by delaying the loading state
            setTimeout(() => {
                isLoadingWeather.value = false;
            }, 2000);
            return Promise.reject(error);
        }

        return Promise.resolve(true);
    }

    onMounted(() => {
        const weatherChannel = echo.value.channel('weather');
        weatherChannel.listen('.error', () => {
            failedLoadingWeather.value = true;
            isLoadingWeather.value = false;
        });

        weatherChannel.listen('.updating', () => {
            failedLoadingWeather.value = false;
            isLoadingWeather.value = true;
        });

        weatherChannel.listen('.updated', (event) => {
            weatherStore.setWeather(event.weatherData);
            isLoadingWeather.value = false;
        });
    });

    onUnmounted(() => {
        echo.value.leave('weather');
    });

    return {
        failedLoadingWeather,
        isLoadingWeather,
        updateWeather
    };
}
