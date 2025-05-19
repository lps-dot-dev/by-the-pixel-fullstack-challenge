import { ref } from "vue";
import { defineStore } from "pinia";

export const useWeatherStore = defineStore("weather", () => {
  /** @type {Map<number,object>} */
  const weather = ref(new Map());

  /**
   * For single insertions
   * 
   * @param {number} userId
   * @param {object} userWeather
   */
  function addWeather(userId, userWeather) {
    if (isNaN(userId) == false) {
      weather.value.set(Number.parseInt(userId), userWeather);
    }
  }

  /**
   * Used for multiple insertions
   * 
   * @param {object} weather must be a key/value object where the key is the user ID and the value is the weather object itself
   */
  function setWeather(weather) {
    const userIds = Object.keys(weather);
    userIds.forEach(userId => {
      addWeather(userId, weather[userId]);
    });
  }

  return { weather, addWeather, setWeather };
});
