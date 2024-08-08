<template>
  <div class="h-auth-block">
    <AFIcon class="h-auth-block__icon" :icon="UserIcon" />
    <div v-if="isAuth" class="h-auth-block__links">
      <RouterLink
        class="h-auth-block__btn link"
        type="button"
        :to="{ name: 'Profile' }"
      >
        Профиль
      </RouterLink>
    </div>
    <div v-else class="h-auth-block__links">
      <button class="h-auth-block__btn link" type="button">Вход</button>
      <div class="h-auth-block__delimeter">/</div>
      <button class="h-auth-block__btn link" type="button">Регистрация</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import UserIcon from "@/assets/images/icons/user.svg"
import AFIcon from "@/components/Blocks/AFIcon.vue"
import { computed } from "vue"

const isAuth = computed(() => true)
</script>

<style lang="scss">
.h-auth-block {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.5rem;

  &__icon {
    font-size: 18px;
    width: 18px;
    height: 18px;
    color: var(--purple-dark);
  }

  &__links {
    color: var(--text-color);
    @include fRegular(14);
    display: flex;
    align-items: center;
  }
}
</style>
