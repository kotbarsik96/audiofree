<template>
  <i class="icon" :class="[`icon-${type}`]" :style="{ '--rotate': rotate }"></i>
</template>

<script setup lang="ts">
defineProps<{
  type: string
  rotate?: string
}>()
</script>

<style lang="scss">
.icon {
  display: inline-block;
  transform: rotate(var(--rotate));
}
</style>