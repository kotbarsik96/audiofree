<template>
  <div class="input-wrapper" :class="className">
    <label v-if="label" class="input-wrapper__label" :for="id">
      {{ label }}
    </label>
    <div class="input-wrapper__wrap">
      <AFIcon v-if="icon" class="input-wrapper__icon" :icon="icon" />
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import AFIcon from "@/components/Blocks/AFIcon.vue"
import { computed } from "vue"

const props = withDefaults(
  defineProps<{
    label?: string
    id?: string
    icon?: string | any
    iconPos?: "right" | "left"
    rounded?: boolean
  }>(),
  {
    iconPos: "right",
  }
)

const className = computed(() => {
  return {
    "input-wrapper--icon-left": props.iconPos === "left",
    "input-wrapper--rounded": props.rounded,
  }
})
</script>

<style lang="scss" scoped>
.input-wrapper {
  --input-icon-size: 23px;
  --input-padding-x: 17px;
  --input-padding-y: 10px;
  --input-icon-padding: 10px;
  --input-w-icon-padding: calc(
    var(--input-padding-x) + var(--input-icon-padding) + var(--input-icon-size)
  );

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
    transform: translateY(-55%);
    color: var(--purple-dark);
    font-size: var(--input-icon-size);
    width: var(--input-icon-size);
    height: var(--input-icon-size);
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

  :deep(.input) {
    border-radius: 9px;
    border: 1px solid #dadada;
    background-color: transparent;
    @include fRegular(14);
    padding: var(--input-padding-y) var(--input-padding-x);
    outline: none;
    width: 100%;
    color: var(--text-color);
  }
  :deep(.input)::placeholder {
    color: #b9b9b9;
  }

  &--rounded :deep(.input) {
    border-radius: 23px;
  }
}
</style>
