import { ref, onMounted, onUnmounted } from 'vue'

export function useShiftKeyWatcher() {
  const shiftPressed = ref(false)

  function checkKeyDown(event: KeyboardEvent) {
    shiftPressed.value = event.key === 'Shift'
  }

  function checkKeyUp(event: KeyboardEvent) {
    shiftPressed.value &&= event.key !== 'Shift'
  }

  onMounted(() => window.addEventListener('keydown', checkKeyDown))
  onMounted(() => window.addEventListener('keyup', checkKeyUp))
  onUnmounted(() => window.removeEventListener('keydown', checkKeyDown))
  onUnmounted(() => window.removeEventListener('keyup', checkKeyUp))

  return shiftPressed
}
