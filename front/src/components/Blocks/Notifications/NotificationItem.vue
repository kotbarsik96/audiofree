<template>
  <div class="notification" :style="styles">
    <button class="notification__close" type="button" @click="close">
      <CloseIcon />
    </button>
    <div class="notification__body">
      <component class="notification__icon" :is="presentationData.icon" />
      <span>
        {{ data.content }}
      </span>
    </div>
    <div class="notification__timer">
      <span></span>
    </div>
  </div>
</template>

<script setup lang="ts">
import SuccessIcon from "@/assets/images/icons/success.svg"
import ErrorIcon from "@/assets/images/icons/error.svg"
import InfoIcon from "@/assets/images/icons/info.svg"
import CloseIcon from "@/assets/images/icons/close.svg"
import { useNotifications } from "@/composables/useNotifications"
import type INotification from "@/interfaces/notification/INotification"
import { computed } from "vue"

const { removeNotification } = useNotifications()

const props = defineProps<{
  data: INotification
}>()

const presentationData = computed(() => {
  let _data = { icon: SuccessIcon, color: "var(--secondary-2)" }

  switch (props.data.severity) {
    case "error":
      _data = {
        icon: ErrorIcon,
        color: "var(--red)",
      }
      break
    case "info":
      _data = {
        icon: InfoIcon,
        color: "var(--text-color)",
      }
      break
    case "success":
      _data = {
        icon: SuccessIcon,
        color: "var(--secondary-2)",
      }
      break
  }

  return _data
})

const styles = computed(() => {
  return {
    "--timer-duration": `${props.data.holdTime / 1000}s`,
    "--n-severity-color": presentationData.value.color,
  }
})

function close() {
  removeNotification(props.data.id)
}
</script>

<style lang="scss" scoped>
.notification {
  --timer-height: 6px;

  position: relative;
  padding: 20px 10px calc(20px + var(--timer-height)) 10px;
  border-radius: 8px;
  overflow: hidden;
  background-color: var(--white);
  color: var(--n-severity-color);
  box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.5);

  &:not(:last-child) {
    margin-bottom: 10px;
  }

  &__close {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;

    svg {
      width: 1.25rem;
      height: 1.25rem;
      color: var(--black);
      z-index: 500;
    }
  }

  &__body {
    @include fMedium(16);
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  &__timer {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: var(--timer-height);
    background-color: var(--stroke);
    overflow: hidden;
    border-radius: 8px;

    span {
      position: absolute;
      display: inline-block;
      left: 0;
      top: 0;
      bottom: 0;
      width: 100%;
      background-color: var(--n-severity-color);
      animation: notificationAnim var(--timer-duration) linear;
      transition: all 1s ease;
    }
  }

  &__icon {
    width: 1.25rem;
    height: 1.25rem;
  }

  @keyframes notificationAnim {
    0% {
      width: 0%;
    }
    100% {
      width: 100%;
    }
  }
}
</style>
