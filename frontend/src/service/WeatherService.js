import { ref } from "vue";
import { useWeatherStore } from "../stores/weather";

export function useWeatherService() {
    const errorOccurred = ref(false);
    const isLoading = ref(false);

    /**
     * Retrieves users from the data store or fetches them from the backend (if necessary)
     * 
     * @param {import('axios').Axios} httpClient 
     * @returns {Promise<Map<number,object>|Error>}
     */
    async function getWeather(httpClient) {
        errorOccurred.value = false;
        isLoading.value = true;

        const weatherStore = useWeatherStore();
        if (weatherStore.weather.length > 0) {
            isLoading.value = false;
            return Promise.resolve(weatherStore.weather);
        }

        try {
            const response = await httpClient.get('/weather');
            if (response.data instanceof Object && Object.keys(response.data).length > 0) {
                weatherStore.setWeather(response.data);
            }
        } catch (error) {
            errorOccurred.value = true;
            isLoading.value = false;
            return Promise.reject(error);
        }

        isLoading.value = false;
        return Promise.resolve(weatherStore.weather);
    }

    return {
        errorOccurred,
        isLoading,
        getWeather
    };
}
