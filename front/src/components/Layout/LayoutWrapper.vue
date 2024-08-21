<template>
  <div class="layout">
    <NotificationsContainer class="layout__notifications" />
    <div class="layout__content">
      <PageHeader />
      <main class="layout__main">
        <slot></slot>
      </main>
      <PageFooter />
    </div>

    <GlobalPreloader v-if="globalPreloaderShown" teleportTo="body" />
  </div>
</template>

<script setup lang="ts">
import PageHeader from "@/components/Layout/LayoutSections/PageHeader.vue"
import PageFooter from "@/components/Layout/LayoutSections/PageFooter.vue"
import NotificationsContainer from "@/components/Blocks/Notifications/NotificationsContainer.vue"
import { useAuthStore } from "@/stores/authStore"
import GlobalPreloader from "@/components/Blocks/GlobalPreloader.vue"
import { useUserStore } from "@/stores/userStore"
import { computed } from "vue"

const authStore = useAuthStore()
const userStore = useUserStore()

/**  прелоадер показывается, если есть хоть одно true-значение в первом массиве,
 * при этом все значения из второго массива == false
*/
const globalPreloaderShown = computed(
  () =>
    [userStore.isLoading].some((value) => !!value) &&
    ![authStore.dialogShown].some((value) => !!value)
)
</script>

<style lang="scss" scoped>
.layout {
  &__content {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  &:has(dialog[open]) &__notifications {
    display: none;
  }
}
</style>
