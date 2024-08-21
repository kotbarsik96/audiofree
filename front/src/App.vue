<template>
  <LayoutWrapper>
    <RouterView />
  </LayoutWrapper>
</template>

<script setup lang="ts">
import LayoutWrapper from "@/components/Layout/LayoutWrapper.vue"
import { useGlobalStore } from "@/stores/globalStore"

useGlobalStore().initApp()
defineIfFirefox()

function defineIfFirefox() {
  const isFirefox = navigator.userAgent.toLowerCase().includes("firefox")
  if (isFirefox) document.body.classList.add("firefox")
}
</script>

<style lang="scss"></style>
