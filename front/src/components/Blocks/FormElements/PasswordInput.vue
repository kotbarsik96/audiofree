<template>
  <InputWrapper class="password-input" :icon="KeyIcon">
    <input
      class="input"
      :type="inputType"
      v-model="password"
      :placeholder="placeholder"
    />
    <template v-if="slots.error" #error>
      <slot name="error" />
    </template>
    <Transition name="fade-in">
      <button
        v-if="password.length > 0"
        class="password-input__btn"
        type="button"
        @click="toggleInputType"
      >
        <EyeIcon />
      </button>
    </Transition>
  </InputWrapper>
</template>

<script setup lang="ts">
import InputWrapper from "@/components/Blocks/FormElements/InputWrapper.vue"
import KeyIcon from "@/assets/images/icons/key.svg"
import EyeIcon from "@/assets/images/icons/eye.svg"
import { computed, ref } from "vue"
import { useSlots } from "vue"

const props = withDefaults(
  defineProps<{
    modelValue: string
    placeholder?: string
  }>(),
  {
    placeholder: "Пароль",
  }
)
const slots = useSlots()

const emit = defineEmits<{
  (e: "update:modelValue", value: string): void
}>()

const password = computed({
  get() {
    return props.modelValue
  },
  set(value) {
    emit("update:modelValue", value)
  },
})

const inputType = ref<"password" | "text">("password")

function toggleInputType() {
  if (inputType.value === "password") inputType.value = "text"
  else inputType.value = "password"
}
</script>

<style lang="scss" scoped>
.password-input {
  &__btn {
    width: 1.25rem;
    height: 1.25rem;
    position: absolute;
    right: var(--input-icon-padding);
    top: 50%;
    transform: translateY(-50%);

    svg {
      width: 100%;
      height: 100%;
      color: var(--primary);
    }

    input {
      padding-right: var(--input-w-icon-padding);
    }
  }
}
</style>
