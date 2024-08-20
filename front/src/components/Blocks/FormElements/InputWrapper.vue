<template>
  <div class="input-wrapper" :class="className">
    <label v-if="label" class="input-wrapper__label" :for="id">
      {{ label }}
    </label>
    <div class="input-wrapper__wrap">
      <AFIcon v-if="icon" class="input-wrapper__icon" :icon="icon" />
      <slot />
      <Transition name="fade-in">
        <span v-if="slots.error" class="input-wrapper__error _error">
          <slot name="error" />
        </span>
      </Transition>
    </div>
  </div>
</template>

<script setup lang="ts">
import AFIcon from "@/components/Blocks/AFIcon.vue"
import { computed } from "vue"
import { useSlots } from "vue"

const props = withDefaults(
  defineProps<{
    label?: string
    id?: string
    icon?: string | any
    iconPos?: "right" | "left"
    rounded?: boolean
  }>(),
  {
    iconPos: "left",
  }
)
const slots = useSlots()

const className = computed(() => {
  return {
    "input-wrapper--icon-left": props.iconPos === "left",
    "input-wrapper--rounded": props.rounded,
  }
})
</script>

<style lang="scss" scoped>
.input-wrapper {
  --input-icon-size: 1.25rem;
  --input-padding-x: 1rem;
  --input-padding-y: 0.6rem;
  --input-icon-padding: 1rem;
  --input-w-icon-padding: calc(
    var(--input-padding-x) + var(--input-icon-padding) +
      calc(var(--input-icon-size) / 1.5)
  );

  position: relative;
  margin-bottom: 0.25rem;

  @include fRegular(14);

  &__label {
    display: inline-block;
    margin-bottom: 5px;
    @include fRegular(12);
  }

  &__wrap {
    position: relative;
  }

  &__icon {
    position: absolute;
    right: var(--input-icon-padding);
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    width: var(--input-icon-size);
    height: var(--input-icon-size);
    width: var(--input-icon-size);
    height: var(--input-icon-size);
    transition: var(--general-transition);
  }
  &__icon + :deep(.input) {
    padding-right: var(--input-w-icon-padding);
  }
  &--icon-left &__icon + :deep(.input) {
    padding-right: var(--input-padding-x);
    padding-left: var(--input-w-icon-padding);
  }

  &--icon-left &__icon {
    right: auto;
    left: var(--input-icon-padding);
  }

  &:has(._error) :deep(.input) {
    border-color: var(--red);
  }
  &:has(._error) &__icon {
    color: var(--red);
  }

  :deep(.input) {
    border-radius: 9px;
    border: 1px solid #dadada;
    background-color: transparent;
    padding: var(--input-padding-y) var(--input-padding-x);
    outline: none;
    width: 100%;
    color: var(--text-color);
    transition: var(--general-transition);
    @include fRegular(14);
  }
  :deep(.input)::placeholder {
    color: #b9b9b9;
  }

  &--rounded :deep(.input) {
    border-radius: 23px;
  }

  &__error {
    position: absolute;
    top: calc(100% + 0.15rem);
    left: 0;
  }
}
</style>
