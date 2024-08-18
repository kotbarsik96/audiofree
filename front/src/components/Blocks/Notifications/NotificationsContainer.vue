<template>
  <div class="notifications-container">
    <TransitionGroup name="notification-anim">
      <NotificationItem
        v-for="item in notifications"
        :key="item.id"
        :data="item"
      />
    </TransitionGroup>
  </div>
</template>

<script setup lang="ts">
import NotificationItem from "@/components/Blocks/Notifications/NotificationItem.vue"
import { useNotifications } from "@/composables/useNotifications"

const { getNotificationsList } = useNotifications()

const notifications = getNotificationsList()
</script>

<style lang="scss" scoped>
.notifications-container {
  position: fixed;
  bottom: 10px;
  right: 10px;
  width: 300px;
  z-index: 9999;
}
</style>
