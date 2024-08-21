<template>
  <Teleport :to="teleportTo" :disabled="!teleportTo">
    <Transition name="fade-in" appear>
      <div class="g-preloader">
        <AFIcon :icon="HeadphonesIcon" />
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import AFIcon from "@/components/Blocks/AFIcon.vue"
import HeadphonesIcon from "@/assets/images/icons/headphones.svg"

const props = defineProps<{
  teleportTo?: string
}>()
</script>

<style lang="scss" scoped>
.g-preloader {
  position: fixed;
  inset: 0;
  z-index: 10000;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;

  svg {
    width: 7rem;
    height: 7rem;
    animation: preloaderAnim 1s linear infinite alternate;
  }
}

@keyframes preloaderAnim {
  0% {
    color: var(--primary-dark);
  }
  100%{ 
    color: var(--text-color);
  }
}
</style>
