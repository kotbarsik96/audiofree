<template>
  <div class="input-wrapper" :class="className">
    <label v-if="label" class="input-wrapper__label" :for="id">
      {{ label }}
    </label>
    <div class="input-wrapper__wrap">
      <Icon v-if="icon" :type="icon" class="input-wrapper__icon" />
      <component
        :is="inputComponent"
        :placeholder="placeholder"
        :id="id"
        v-bind="inputProps"
        @input="onInput"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import Icon from "@/components/Blocks/Icon.vue"
import TextInput from "@/components/Blocks/FormElements/TextInput.vue"
import NumberInput from "@/components/Blocks/FormElements/NumberInput.vue"
import { computed } from "vue"

const props = withDefaults(
  defineProps<{
    modelValue?: string | number
    type?: "text" | "number"
    label?: string
    id: string
    inputProps?: { [key: string]: any }
    icon?: string
    iconPos?: "right" | "left"
    placeholder?: string
    rounded?: boolean
  }>(),
  {
    type: "text",
    iconPos: "right",
  }
)

const emit = defineEmits<{
  (e: "update:modelValue", value: typeof props.modelValue): void
}>()

const inputComponent = computed(() => {
  switch (props.type) {
    case "number":
      return NumberInput
    case "text":
    default:
      return TextInput
  }
})

const className = computed(() => {
  return {
    "input-wrapper--icon-left": props.iconPos === "left",
    "input-wrapper--rounded": props.rounded,
  }
})

function onInput(value?: string | number) {
  emit("update:modelValue", value)
}
</script>

<style lang="scss">
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
  }
  &__icon + .input {
    padding-right: var(--input-w-icon-padding);
  }
  &--icon-left &__icon + .input {
    padding-right: var(--input-padding-x);
    padding-left: var(--input-w-icon-padding);
  }

  &--icon-left &__icon {
    right: auto;
    left: var(--input-icon-padding);
  }

  &--rounded .input {
    border-radius: 23px;
  }
}

.input {
  border-radius: 9px;
  border: 1px solid #dadada;
  background-color: transparent;
  @include fRegular(14);
  padding: var(--input-padding-y) var(--input-padding-x);
  outline: none;
  width: 100%;
  color: var(--text-color);

  &::placeholder {
    color: #b9b9b9;
  }
}
</style>
