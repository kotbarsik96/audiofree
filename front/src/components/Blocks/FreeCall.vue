<template>
  <div class="free-call">
    <ButtonIcon
      class="free-call__icon"
      :icon="PhoneIcon"
      type="link"
      href="tel:7777777777"
      shadow
    />
    <div class="free-call__text">
      <div class="free-call__title">Бесплатный звонок по РФ</div>
      <a class="free-call__number" href="tel:7777777777">9 8010 111-11-11</a>
    </div>
  </div>
</template>

<script setup lang="ts">
import ButtonIcon from "@/components/Blocks/ButtonIcon.vue"
import PhoneIcon from "@/assets/images/icons/phone.svg"
</script>

<style lang="scss" scoped>
.free-call {
  display: flex;
  gap: 15px;

  &__title {
    color: #aeaeae;
    @include fRegular(12);
  }

  &__number {
    color: var(--text-color);
    @include fBold(24);
  }
}
</style>
