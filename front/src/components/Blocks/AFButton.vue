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
    styleType?: "primary" | "secondary"
    label?: string
    icon?: string | any
    iconPos?: "left" | "right"
  }>(),
  {
    type: "button",
    iconPos: "left",
    styleType: "primary",
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
    `btn--${props.styleType}`,
    {
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
  background-color: var(--primary);
  color: var(--white);
  padding: 12px 16px;
  border-radius: 20px;
  overflow: hidden;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border: 1px solid transparent;
  @include fBold(14);
  transition: var(--general-transition);

  &:hover:not(:disabled) {
    box-shadow: 0px 8px 5px rgba(139, 117, 200, 0.25);
  }

  &--secondary {
    background-color: transparent;
    box-shadow: none;
    border-color: #a9a9a9;
    color: var(--text-color);
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
  &--bg-yellow {
    background-color: var(--yellow);
  }
  &--bg-green {
    background-color: var(--secondary-1);
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
