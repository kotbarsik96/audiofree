import { onMounted, onUnmounted, ref } from "vue"

export function useTimer(interval: number = 1000, from = Date.now()) {
  const timeSource = ref(from)
  let lastUpdatedTime = from

  let intervalCallback: ReturnType<typeof setInterval>

  onMounted(() => {
    intervalCallback = setInterval(() => {
      const newTime = Date.now()
      timeSource.value = timeSource.value + (newTime - lastUpdatedTime)
      lastUpdatedTime = newTime
    }, interval)
  })

  onUnmounted(() => {
    if (intervalCallback) clearInterval(intervalCallback)
  })

  return {
    timeSource,
    timeFrom: from,
  }
}
