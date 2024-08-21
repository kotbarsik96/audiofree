<template>
  <div class="login-form">
    <form class="login-form__form auth-form" @submit.prevent="onSubmit">
      <InputWrapper class="auth-form__input" type="email" :icon="UserIcon">
        <TextInput v-model="name" placeholder="Ваше имя" />
        <template v-if="errors?.name" #error>
          {{ errors.name[0] }}
        </template>
      </InputWrapper>
      <InputWrapper class="auth-form__input" :icon="MailIcon">
        <TextInput v-model="email" placeholder="Email" autocomplete="email" />
        <template v-if="errors?.email" #error>
          {{ errors.email[0] }}
        </template>
      </InputWrapper>
      <PasswordInput class="auth-form__input" v-model="password" autocomplete="new-password">
        <template v-if="errors?.password" #error>
          {{ errors.password[0] }}
        </template>
      </PasswordInput>
      <PasswordInput
        class="auth-form__input"
        v-model="passwordRepeat"
        placeholder="Пароль еще раз"
        autocomplete="new-password"
      />
      <div class="auth-form__buttons">
        <AFButton type="submit" label="Войти" :disabled="isLoading" />
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import InputWrapper from "@/components/Blocks/FormElements/InputWrapper.vue"
import TextInput from "@/components/Blocks/FormElements/TextInput.vue"
import AFButton from "@/components/Blocks/AFButton.vue"
import UserIcon from "@/assets/images/icons/user.svg"
import MailIcon from "@/assets/images/icons/mail.svg"
import PasswordInput from "@/components/Blocks/FormElements/PasswordInput.vue"
import { ref } from "vue"
import { storeToRefs } from "pinia"
import { useAuthStore } from "@/stores/authStore"
import { useUserStore } from "@/stores/userStore"
import UserService from "@/services/User/UserService"
import type { IErrors } from "@/api/interfaces/IError"
import { useNotifications } from "@/composables/useNotifications"

const userService = new UserService()
const { token } = storeToRefs(useUserStore())
const { email, dialogShown } = storeToRefs(useAuthStore())
const { addNotification } = useNotifications()

const name = ref("")
const password = ref("")
const passwordRepeat = ref("")
const errors = ref<IErrors>()
const isLoading = ref(false)

async function onSubmit() {
  isLoading.value = true

  const response = await userService.signup({
    email: email.value,
    name: name.value,
    password: password.value,
    password_confirmation: passwordRepeat.value,
  })
  if (response?.errors) errors.value = response.errors
  else if (response) {
    token.value = response.data.data.token
    dialogShown.value = false
    if (response.data.message) addNotification("info", response.data.message)
  }

  isLoading.value = false
}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";

.signup-form {
  min-height: 19rem;
}
</style>
