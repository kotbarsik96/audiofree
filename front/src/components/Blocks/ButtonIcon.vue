<template>
  <component :is="component" class="btn-icon" :class="className" v-bind="attrs">
    <div v-if="badge || badge === 0" class="btn-icon__badge">{{ badge }}</div>
    <Icon :type="icon" />
  </component>
</template>

<script setup lang="ts">
import Icon from "@/components/Blocks/Icon.vue"
import { computed } from "vue"
import { RouterLink } from "vue-router"

const props = withDefaults(
  defineProps<{
    type?: "button" | "link" | "router-link"
    icon: string
    shadow?: boolean
    badge?: string | number
  }>(),
  {
    type: "button",
  }
)

const component = computed(() => {
  switch (props.type) {
    case "button":
    default:
      return "button"
    case "link":
      return "a"
    case "router-link":
      return RouterLink
  }
})
const attrs = computed(() => {
  switch (props.type) {
    case "button":
    default:
      return { type: "button" }
    case "router-link":
      return {}
  }
})

const className = computed(() => {
  return { shadow: props.shadow }
})
</script>

<style lang="scss">
.btn-icon {
  position: relative;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2.43rem;
  height: 2.43rem;
  border-radius: 50%;
  background-color: var(--white);
  color: var(--purple-dark);
  transition: var(--general-transition);

  .icon {
    font-size: 1.5rem;
  }

  &.shadow {
    box-shadow: 0 0.31rem 0.975rem rgba(140, 121, 199, 0.3);
  }

  &:hover {
    background-color: var(--green-2);
    color: var(--white);
  }

  &__badge {
    position: absolute;
    top: -0.31rem;
    right: -0.31rem;
    height: 1rem;
    width: 1rem;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: var(--green-3);
    color: var(--white);
    font-size: 0.7rem;
    font-weight: 700;
  }

  @include adaptive(tablet-big) {
    background-color: transparent;
    width: auto;
    height: auto;

    .icon {
      font-size: 1.25rem;
    }

    &.shadow {
      box-shadow: none;
    }
  }
}
</style>
