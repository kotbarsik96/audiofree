<template>
  <ul class="socials-list">
    <li v-for="item in socials" :key="item.title">
      <component class="socials-list__icon" :is="item.icon" />
    </li>
  </ul>
</template>

<script setup lang="ts">
import socials from "@/enums/socials"
</script>

<style lang="scss" scoped>
.socials-list {
  display: flex;
  gap: 9px;

  &__icon {
    font-size: 26px;
    color: var(--white);
    opacity: 0.2;
  }
}
</style>
