import { useWeatherStore } from "../stores/weather";

/**
 * Retrieves users from the data store or fetches them from the backend (if necessary)
 * 
 * @param {import('axios').Axios} httpClient 
 * @returns {Promise<Map<number,object>|Error>}
 */
async function getWeather(httpClient) {
    const weatherStore = useWeatherStore();
    if (weatherStore.weather.length > 0) {
        return Promise.resolve(weatherStore.weather);
    }

    try {
        const response = await httpClient.get('/weather');
        if (response.data instanceof Object && Object.keys(response.data).length > 0) {
            weatherStore.setWeather(response.data);
        }
    } catch (error) {
        return Promise.reject(error);
    }

    return Promise.resolve(weatherStore.weather);
}

export { getWeather };