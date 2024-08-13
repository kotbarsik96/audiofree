<template>
  <AFDialog class="auth-dialog" v-model:shown="_shown">
    <div class="auth-dialog__inner">
      <div class="auth-dialog__tabs">
        <button
          class="auth-dialog__tab-btn"
          :class="{ active: _tab === 'login' }"
          type="button"
          @click="_tab = 'login'"
        >
          Вход
        </button>
        <button
          class="auth-dialog__tab-btn"
          :class="{ active: _tab === 'signup' }"
          type="button"
          @click="_tab = 'signup'"
        >
          Регистрация
        </button>
      </div>
      <div class="auth-dialog__body">
        <div class="auth-dialog__title">Авторизация</div>
        <Transition name="fade-in" mode="out-in">
          <component :is="component" />
        </Transition>
      </div>
    </div>
  </AFDialog>
</template>

<script setup lang="ts">
import AFDialog from "@/components/Blocks/Dialog/AFDialog.vue"
import LoginForm from "@/components/Blocks/AuthForms/LoginForm.vue"
import SignupForm from "@/components/Blocks/AuthForms/SignupForm.vue"
import type { authTabs } from "@/enums/auth/authTabs"
import { computed } from "vue"

const props = defineProps<{
  tab: authTabs
  shown?: boolean
}>()

const emit = defineEmits<{
  (e: "update:shown", bool: boolean): void
  (e: "update:tab", newTab: authTabs): void
}>()

const _shown = computed({
  get() {
    return props.shown
  },
  set(bool) {
    emit("update:shown", bool)
  },
})

const _tab = computed({
  get() {
    return props.tab
  },
  set(newTab) {
    emit("update:tab", newTab)
  },
})

const component = computed(() => {
  let _component: typeof SignupForm | typeof LoginForm

  switch (props.tab) {
    case "login":
      _component = LoginForm
      break
    case "signup":
      _component = SignupForm
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
