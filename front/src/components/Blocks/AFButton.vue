<template>
  <component :is="component" class="btn" :class="className" v-bind="attrs">
    <span class="btn__inner">
      <AFIcon :icon="icon" class="btn__icon" />
      <span v-if="label" class="btn__label">{{ label }}</span>
    </span>
  </component>
</template>

<script setup lang="ts">
import AFIcon from "@/components/Blocks/AFIcon.vue"
import { computed } from "vue"
import { RouterLink } from "vue-router"

const props = withDefaults(
  defineProps<{
    type?: "button" | "submit" | "reset" | "router-link"
    label?: string
    icon: string | any
    iconPos?: "left" | "right"
    bg?: "green" | "yellow"
  }>(),
  {
    type: "button",
    iconPos: "left",
  }
)

const component = computed(() => {
  switch (props.type) {
    case "router-link":
      return RouterLink
    default:
      return "button"
  }
})

const className = computed(() => {
  return [
    {
      "btn--bg-green": props.bg === "green",
      "btn--bg-yellow": props.bg === "yellow",
      "btn--icon-right": props.iconPos === "right",
      "btn--icon-only": !props.label && props.icon,
    },
  ]
})
const attrs = computed(() => {
  switch (props.type) {
    case "router-link":
      return {}
    default:
      return {
        type: props.type,
        "aria-label": props.label,
      }
  }
})
</script>

<style lang="scss" scoped>
.btn {
  // styles
  position: relative;
  transition: var(--general-transition);
  background-color: var(--purple);
  color: var(--bg);
  padding: 20px;
  border-radius: 12px;
  overflow: hidden;
  display: inline-flex;
  align-items: center;
  justify-content: center;

  &:hover:not(:disabled) {
    background-color: var(--green-1);
  }

  &__inner {
    position: relative;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
  }
  &--icon-right &__inner {
    flex-direction: row-reverse;
  }

  &__label {
    @include fBold(14);
  }

  &__icon {
    font-size: 20px;
    width: 20px;
    height: 20px;
  }

  // modifiers
  &--icon-only {
    border-radius: 50%;
    width: 40px;
    height: 40px;
  }
  &[class*="btn--bg-"] {
    &::before {
      opacity: 0;
    }
    &:hover:not(:disabled) {
      background-color: var(--blue);
    }
  }
  &--bg-yellow {
    background-color: var(--yellow);
  }
  &--bg-green {
    background-color: var(--green-1);
  }

  &:disabled,
  &:disabled:hover {
    cursor: default;
    pointer-events: none;
    color: #aeaeae;
    background-color: #656565;
  }
}
</style>
