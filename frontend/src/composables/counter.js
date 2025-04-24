import { ref, onMounted, onUnmounted } from 'vue'

export function useCounter() {
    const count = ref(0);
    let intervalId;

    onMounted(() => {
        intervalId = setInterval(() => {
            count.value++;
        }, 1000);
    });

    onUnmounted(() => {
        clearInterval(intervalId);
    });

    function resetCounter() {
        count.value = 0;
    }

    return { count, resetCounter };
}
