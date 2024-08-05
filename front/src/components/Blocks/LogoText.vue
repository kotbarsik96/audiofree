<template>
  <component :is="component" class="logo-text" :class="className" :to="to">
    <span class="logo-text__logo">
      <Icon v-if="!hideIcon" type="headphones" />
      Audiofree
      <span v-if="!hideText">|</span>
    </span>
    <span v-if="!hideText" class="logo-text__text">
      Интернет магазин беспроводных наушников по РФ
    </span>
  </component>
</template>

<script setup lang="ts">
import Icon from "@/components/Blocks/Icon.vue"
import { RouterLink } from "vue-router"
import { computed } from "vue"

const props = defineProps<{
  link?: boolean
  hideIcon?: boolean
  hideText?: boolean
}>()

const component = computed(() => (props.link ? RouterLink : "span"))
const to = computed(() => (props.link ? { name: "Home" } : null))

const className = computed(() => {
  return {
    "logo-text--link": props.link,
  }
})
</script>

<style lang="scss" scoped>
.logo-text {
  display: flex;
  align-items: center;
  gap: 5px;

  &__logo {
    display: flex;
    align-items: center;
    gap: 9px;
    @include fBold(18);
    transition: var(--general-transition);

    .icon {
      font-size: 21px;
    }
  }

  &__text {
    @include fRegular(12);
    line-height: 1;
    transition: var(--general-transition);
  }

  &--link:hover &__logo,
  &--link:hover &__text {
    color: var(--green-2);
  }
}
</style>
