<template>
  <div class="layout">
    <div class="layout__content"> 
      <slot></slot>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>

<style lang="scss">
.layout{
  &__content {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
}
</style>