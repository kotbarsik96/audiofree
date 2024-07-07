<template>
  <LayoutWrapper>
    <PageHeader />
    <RouterView />
  </LayoutWrapper>
</template>

<script setup lang="ts">
import PageHeader from "@/components/Layout/LayoutSections/PageHeader.vue"
import LayoutWrapper from "@/components/Layout/LayoutWrapper.vue"

defineIfFirefox()

function defineIfFirefox() {
  const isFirefox = navigator.userAgent.toLowerCase().includes("firefox")
  if (isFirefox) document.body.classList.add("firefox")
}
</script>

<style lang="scss"></style>
