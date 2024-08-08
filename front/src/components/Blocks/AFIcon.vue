<template>
  <img v-if="typeof icon === 'string'" class="icon" :src="icon" />
  <component v-else class="icon" :is="icon" />
</template>

<script setup lang="ts">
import { computed } from "vue"

const props = defineProps<{
  icon: string | any
  rotate?: string
}>()

const style = computed(() => {
  return { "--rotate": props.rotate }
})
</script>

<style lang="scss" scoped>
.icon {
  display: inline-block;
  transform: rotate(var(--rotate));
}
</style>
