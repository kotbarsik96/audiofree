<template>
  <div class="dropdown" :class="{ shown }" ref="el">
    <div class="dropdown__value" @click="shown = !shown">
      <div v-if="!modelValue && placeholder" class="dropdown__placeholder">
        {{ placeholder }}
      </div>
      <div v-if="selectedOption" class="dropdown__value-text">
        {{
          typeof selectedOption === "string"
            ? selectedOption
            : selectedOption.value
        }}
      </div>
      <AFIcon class="dropdown__icon" :icon="TriangleRightIcon" rotate="90deg" />
    </div>
    <Transition name="dropdown">
      <ul v-show="shown" class="dropdown__options">
        <li
          v-for="opt in options"
          :key="typeof opt === 'string' ? opt : opt.value"
          class="dropdown__option"
          @click="updateValue(opt)"
        >
          {{ typeof opt === "string" ? opt : opt.text }}
        </li>
      </ul>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import TriangleRightIcon from "@/assets/images/icons/chevron-right.svg"
import { onMounted, onUnmounted, ref } from "vue"

interface IDropdownOption {
  value: string
  text: string
}
type DropdownOption = string | IDropdownOption

const props = defineProps<{
  options: DropdownOption[]
  modelValue: string
  placeholder?: string
}>()

const emit = defineEmits<{
  (e: "update:modelValue", value: typeof props.modelValue): void
}>()

const initialOption = props.options.find((opt) =>
  typeof opt === "string"
    ? opt === props.modelValue
    : opt.value === props.modelValue
)
const selectedOption = ref<DropdownOption | undefined>(initialOption)

const el = ref<HTMLElement>()
const shown = ref<boolean>(false)

onMounted(() => {
  document.documentElement.addEventListener("click", onDocumentClick)
})
onUnmounted(() => {
  document.documentElement.removeEventListener("click", onDocumentClick)
})

function onDocumentClick(event: Event) {
  const target = event.target as HTMLElement

  if (el.value?.contains(target)) return

  shown.value = false
}
function updateValue(option: DropdownOption) {
  selectedOption.value = option
  emit("update:modelValue", typeof option === "string" ? option : option.value)
  shown.value = false
}
</script>

<style lang="scss" scoped>
.dropdown {
  --s-border: 1px solid var(--grey-stroke);
  --s-bg: var(--grey-bg-2);
  --s-bradius: 12px;

  border: var(--s-border);
  background-color: var(--s-bg);
  border-radius: var(--s-bradius);
  color: var(--grey-text);
  position: relative;
  transition: var(--general-transition);
  z-index: 50;
  @include fRegular(14);

  &.shown {
    border-radius: var(--s-bradius) var(--s-bradius) 0 0;
    border-bottom-color: transparent;
  }

  &__value {
    cursor: pointer;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  &__placeholder {
    flex: 1 1 auto;
  }

  &__value-text {
    flex: 1 1 auto;
    color: var(--white);
  }

  &__icon {
    flex: 0 0 10px;
    font-size: 10px;
    color: var(--white);
    transform: rotate(90deg);
    transition: var(--general-transition);
  }
  &.shown &__icon {
    transform: rotate(270deg);
  }

  &__options {
    background-color: var(--s-bg);
    border: var(--s-border);
    border-top: none;
    border-radius: 0 0 var(--s-bradius) var(--s-bradius);
    position: absolute;
    top: 100%;
    left: -1px;
    right: -1px;
    overflow: hidden;
  }

  &__option {
    cursor: pointer;
    padding: 20px;
    transition: var(--general-transition);

    &:hover {
      background-color: var(--grey-bg-1);
    }
  }
}

// ainm
.dropdown {
  &-enter-active,
  &-leave-active {
    transition: var(--general-transition);
  }

  &-enter-from,
  &-leave-to {
    opacity: 0;
    transform: translateY(-10px);
  }

  &-enter-to,
  &-leave-from {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
