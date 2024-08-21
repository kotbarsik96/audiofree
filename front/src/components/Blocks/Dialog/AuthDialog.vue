<template>
  <AFDialog class="auth-dialog" v-model:shown="_shown">
    <div class="auth-dialog__inner">
      <Transition name="fade-in">
        <div v-if="tab !== 'reset'" class="auth-dialog__tabs">
          <button
            class="auth-dialog__tab-btn"
            :class="{ active: tab === 'login' }"
            type="button"
            @click="tab = 'login'"
          >
            Вход
          </button>
          <button
            class="auth-dialog__tab-btn"
            :class="{ active: tab === 'signup' }"
            type="button"
            @click="tab = 'signup'"
          >
            Регистрация
          </button>
        </div>
      </Transition>
      <div class="auth-dialog__body">
        <div class="auth-dialog__title">Авторизация</div>
        <Transition name="fade-in" mode="out-in">
          <component :is="component" />
        </Transition>
      </div>
    </div>
    
    <GlobalPreloader v-if="isLoading" />
  </AFDialog>
</template>

<script setup lang="ts">
import AFDialog from "@/components/Blocks/Dialog/AFDialog.vue"
import LoginForm from "@/components/Blocks/AuthForms/LoginForm.vue"
import SignupForm from "@/components/Blocks/AuthForms/SignupForm.vue"
import ResetPasswordForm from "@/components/Blocks/AuthForms/ResetPasswordForm.vue"
import GlobalPreloader from "@/components/Blocks/GlobalPreloader.vue"
import type { authTabs } from "@/enums/auth/authTabs"
import { computed } from "vue"
import { useAuthStore } from "@/stores/authStore"
import { storeToRefs } from "pinia"
import { useUserStore } from "@/stores/userStore"

const props = defineProps<{
  shown?: boolean
}>()

const emit = defineEmits<{
  (e: "update:shown", bool: boolean): void
  (e: "update:tab", newTab: authTabs): void
}>()

const { isLoading } = storeToRefs(useUserStore())
const { tab } = storeToRefs(useAuthStore())

const _shown = computed({
  get() {
    return props.shown
  },
  set(bool) {
    emit("update:shown", bool)
  },
})

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
.auth-dialog {
  min-width: 25rem;

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
