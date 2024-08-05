import type { NotificationSeverity } from "@/enums/notification/NotificationSeverety"

export default interface INotification {
  id: string
  severity: NotificationSeverity
  content: string
  createdAt: Date
  holdTime: number
}
