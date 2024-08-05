<template>
  <input class="input text-input" type="text" @input="onInput" />
</template>

<script setup lang="ts">
const emit = defineEmits<{
  (e: "input", value: string): void
}>()

function onInput(event: Event) {
  const { value } = event.target as HTMLInputElement
  emit("input", value)
}
</script>
