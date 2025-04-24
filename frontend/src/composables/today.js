import { ref, onMounted, onUnmounted } from 'vue';
import moment from 'moment';

export function useToday() {
  const today = ref(moment());
  let animationFrameId;

  const tick = () => {
    const current = moment();
    // Only update if day has changed
    if (!current.isSame(today.value, 'day')) {
      today.value = current;
    }
    animationFrameId = requestAnimationFrame(tick);
  }

  onMounted(() => {
    animationFrameId = requestAnimationFrame(tick);
  });

  onUnmounted(() => {
    cancelAnimationFrame(animationFrameId);
  });

  return { today };
}
