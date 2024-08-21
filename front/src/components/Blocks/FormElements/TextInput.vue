<template>
  <input class="input text-input" :type="type" v-model="value" :autocomplete="autocomplete" />
</template>

<script setup lang="ts">
import { computed } from "vue"

const props = withDefaults(
  defineProps<{
    type?: "text" | "email"
    modelValue: string,
    autocomplete?: string
  }>(),
  {
    type: "text",
  }
)

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void
}>()

const value = computed({
  get() {
    return props.modelValue
  },
  set(val) {
    emit("update:modelValue", val)
  },
})
</script>
