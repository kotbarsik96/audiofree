<template>
  <div class="layout">
    <NotificationsContainer />
    <div class="layout__content">
      <PageHeader />
      <main class="layout__main">
        <slot></slot>
      </main>
      <PageFooter />
    </div>
  </div>
</template>

<script setup lang="ts">
import PageHeader from "@/components/Layout/LayoutSections/PageHeader.vue"
import PageFooter from "@/components/Layout/LayoutSections/PageFooter.vue"
import NotificationsContainer from "@/components/Blocks/Notifications/NotificationsContainer.vue"
</script>

<style lang="scss">
.layout {
  &__content {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
}
</style>
