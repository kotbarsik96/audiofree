<template>
  <dialog
    class="dialog"
    ref="el"
    @close="closeDialog"
    @pointerdown="onPointerdown"
  >
    <div class="dialog__inner" @click.stop>
      <div class="dialog__head">
        <button type="button" @click="closeDialog">
          <AFIcon :icon="CloseIcon" />
        </button>
      </div>
      <slot />
    </div>
  </dialog>
</template>

<script setup lang="ts">
import AFIcon from "@/components/Blocks/AFIcon.vue"
import CloseIcon from "@/assets/images/icons/close.svg"
import { setBodyScroll, hideBodyScroll } from "@/utils/scrollbarHelpers"
import { ref, watch } from "vue"

const props = defineProps<{
  shown?: boolean
}>()

const emit = defineEmits<{
  (e: "update:shown", value: boolean): void
}>()

const el = ref<HTMLDialogElement>()

const dialogShown = ref(false)

watch(
  () => props.shown,
  () => {
    if (props.shown) showDialog()
    else closeDialog()
  }
)
watch(dialogShown, () => emit("update:shown", dialogShown.value))

function closeDialog() {
  dialogShown.value = false
  setBodyScroll()
  el.value?.close()
}
function showDialog() {
  if (el.value) {
    dialogShown.value = true
    hideBodyScroll()
    el.value.showModal()
  }
}
function onPointerdown(event: PointerEvent) {
  if (!el.value || event.target !== el.value) return

  document.addEventListener("pointerup", onPointerup)

  function onPointerup(upEvent: PointerEvent) {
    document.removeEventListener("pointerup", onPointerup)
    if (upEvent.target !== el.value) return

    closeDialog()
  }
}
</script>

<style lang="scss" scoped>
.dialog {
  border: none;
  padding: 0;
  background-color: transparent;
  display: none;
  position: fixed;
  inset: 0;
  animation: hideDialog 0.3s ease-in-out;

  &::backdrop {
    background-color: rgba(0, 0, 0, 0.35);
  }

  &[open] {
    display: block;
    animation: openDialog 0.3s ease-in-out;
  }

  &__head {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 5px 10px;
    border-bottom: 1px solid var(--stroke);

    button {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 1.25rem;
      height: 1.25rem;
    }

    svg {
      width: 100%;
      height: 100%;
    }
  }

  &__inner {
    background-color: var(--white);
    border-radius: 8px;
  }
}

@keyframes openDialog {
  0% {
    opacity: 0;
    transform: scale(0.5);
    display: none;
  }
  100% {
    opacity: 1;
    transform: scale(1);
    display: block;
  }
}
@keyframes hideDialog {
  0% {
    opacity: 1;
    transform: scale(1);
    display: block;
  }
  100% {
    opacity: 0;
    transform: scale(0.5);
    display: none;
  }
}
</style>
