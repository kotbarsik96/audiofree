import { computed, ref } from "vue"
import type INotification from "@/interfaces/notification/INotification"
import type { NotificationSeverity } from "@/enums/notification/NotificationSeverety"
import { useTimer } from "@/composables/useTimer"

const notifications = ref<INotification[]>([])
const _default_notification_hold_time = 7500
const _max_notifications = 10

export function useNotifications() {
  const { timeSource } = useTimer()

  function addNotification(
    severity: NotificationSeverity,
    content: string,
    holdTime?: number
  ) {
    notifications.value.push({
      id: self.crypto.randomUUID(),
      severity,
      content,
      createdAt: new Date(),
      holdTime: holdTime || _default_notification_hold_time,
    })
  }
  function getNotificationsList() {
    return computed(() => {
      return notifications.value
        .filter(
          (item) => timeSource.value < item.createdAt.getTime() + item.holdTime
        )
        .slice(0, _max_notifications)
    })
  }
  function removeNotification(id: string) {
    notifications.value = notifications.value.filter((item) => item.id !== id)
  }

  return {
    addNotification,
    getNotificationsList,
    removeNotification,
  }
}
