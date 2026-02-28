import { ref } from 'vue'

const notifications = ref([])
let counter = 0

export function useNotification() {
  function push(type, message, duration = 4000) {
    const id = ++counter
    notifications.value.push({ id, type, message })
    setTimeout(() => {
      notifications.value = notifications.value.filter((n) => n.id !== id)
    }, duration)
  }

  return {
    notifications,
    success: (msg, dur) => push('success', msg, dur),
    error: (msg, dur) => push('error', msg, dur),
    info: (msg, dur) => push('info', msg, dur),
    warning: (msg, dur) => push('warning', msg, dur),
  }
}
