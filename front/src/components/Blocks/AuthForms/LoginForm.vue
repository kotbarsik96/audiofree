<template>
  <form class="login-form auth-form" @submit.prevent="onSubmit">
    <InputWrapper class="auth-form__input" :icon="MailIcon">
      <TextInput v-model="email" placeholder="Email" autocomplete="username" />
      <template v-if="errors?.email" #error>
        {{ errors.email[0] }}
      </template>
    </InputWrapper>
    <PasswordInput
      class="auth-form__input"
      autocomplete="current-password"
      v-model="password"
    >
      <template v-if="errors?.password" #error>
        {{ errors.password[0] }}
      </template>
    </PasswordInput>
    <div class="auth-form__buttons">
      <button
        class="_link"
        @click="tab = 'reset'"
        type="button"
        :disabled="isLoading"
      >
        Забыли пароль?
      </button>
      <AFButton type="submit" label="Войти" :disabled="isLoading" />
    </div>
  </form>
</template>

<script setup lang="ts">
import InputWrapper from "@/components/Blocks/FormElements/InputWrapper.vue"
import TextInput from "@/components/Blocks/FormElements/TextInput.vue"
import AFButton from "@/components/Blocks/AFButton.vue"
import MailIcon from "@/assets/images/icons/mail.svg"
import PasswordInput from "@/components/Blocks/FormElements/PasswordInput.vue"
import { ref } from "vue"
import { storeToRefs } from "pinia"
import UserApiService from "@/services/User/UserApiService"
import { useAuthStore } from "@/stores/authStore"
import type { IErrors } from "@/api/interfaces/IError"
import { useUserStore } from "@/stores/userStore"
import { useNotifications } from "@/composables/useNotifications"

const userService = new UserApiService()
const { token } = storeToRefs(useUserStore())
const { tab, email, dialogShown } = storeToRefs(useAuthStore())
const { addNotification } = useNotifications()

const password = ref("")
const errors = ref<IErrors>()
const isLoading = ref(false)

async function onSubmit() {
  isLoading.value = true

  const response = await userService.login({
    email: email.value,
    password: password.value,
  })
  if (response?.errors) errors.value = response.errors
  else if (response?.payload) {
    token.value = response.payload.data.token
    dialogShown.value = false
    if (response.payload.message) addNotification("info", response.payload.message)
  }

  isLoading.value = false
}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";

.login-form {
  min-height: 12rem;
}
</style>
