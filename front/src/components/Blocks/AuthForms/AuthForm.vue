<template>
  <div class="auth-form">
    <Transition name="fade-in">
      <div v-if="tab !== 'reset'" class="auth-form__tabs">
        <button
          class="auth-form__tab-btn"
          :class="{ active: tab === 'login' }"
          type="button"
          @click="tab = 'login'"
        >
          Вход
        </button>
        <button
          class="auth-form__tab-btn"
          :class="{ active: tab === 'signup' }"
          type="button"
          @click="tab = 'signup'"
        >
          Регистрация
        </button>
      </div>
    </Transition>
    <div class="auth-form__body">
      <div class="auth-form__title">Авторизация</div>
      <Transition name="fade-in" mode="out-in">
        <component :is="component" />
      </Transition>
    </div>

    <GlobalPreloader v-if="isLoading" />
  </div>
</template>

<script setup lang="ts">
import LoginForm from "@/components/Blocks/AuthForms/LoginForm.vue"
import ResetPasswordForm from "@/components/Blocks/AuthForms/ResetPasswordForm.vue"
import SignupForm from "@/components/Blocks/AuthForms/SignupForm.vue"
import GlobalPreloader from "@/components/Blocks/GlobalPreloader.vue"
import { useAuthStore } from "@/stores/authStore"
import { useUserStore } from "@/stores/userStore"
import { storeToRefs } from "pinia"
import { computed } from "vue"

const { isLoading } = storeToRefs(useUserStore())
const { tab } = storeToRefs(useAuthStore())

const component = computed(() => {
  let _component:
    | typeof SignupForm
    | typeof LoginForm
    | typeof ResetPasswordForm

  switch (tab.value) {
    case "login":
      _component = LoginForm
      break
    case "signup":
      _component = SignupForm
      break
    case "reset":
      _component = ResetPasswordForm
      break
  }

  return _component
})
</script>

<style lang="scss" scoped>
.auth-form {
  &__tabs {
    display: flex;
  }

  &__tab-btn {
    flex: 1 1 50%;
    padding: 0.6rem 1rem;
    @include fMedium(16);
    background-color: var(--stroke);
    color: #9a9a9a;
    transition: var(--general-transition);

    &.active {
      background-color: var(--white);
      color: var(--black);
    }
  }

  &__body {
    padding: 1rem;
  }

  &__title {
    @include fBold(18);
    text-align: center;
    margin-bottom: 1rem;
  }
}
</style>
