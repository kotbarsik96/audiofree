<template>
  <div class="reset-password">
    <div class="_container">
      <form class="auth-form" @submit.prevent="onSubmit">
        <PasswordInput
          class="auth-form__input"
          autocomplete="new-password"
          label="Новый пароль"
          v-model="password"
          id="new-password"
        >
          <template v-if="errors?.password" #error>
            {{ errors.password[0] }}
          </template>
        </PasswordInput>
        <PasswordInput
          class="auth-form__input"
          autocomplete="new-password"
          label="Новый пароль еще раз"
          v-model="passwordRepeat"
          id="new-password-repeat"
        />
        <div class="auth-form__buttons">
          <AFButton type="submit" label="Применить" :disabled="isLoading" />
        </div>
      </form>
    </div>

    <GlobalPreloader v-if="isLoading" />
  </div>
</template>

<script setup lang="ts">
import PasswordInput from "@/components/Blocks/FormElements/PasswordInput.vue"
import GlobalPreloader from "@/components/Blocks/GlobalPreloader.vue"
import AFButton from "@/components/Blocks/AFButton.vue"
import type { IErrors } from "@/api/interfaces/IError"
import { ref } from "vue"
import { useRoute } from "vue-router"
import UserService from "@/services/User/UserService"

const route = useRoute()
const userService = new UserService()

const { code, email } = route.query
const errors = ref<IErrors>()
const password = ref("")
const passwordRepeat = ref("")
const isLoading = ref(false)

async function onSubmit() {
  isLoading.value = true

  const response = await userService.checkPasswordResetLink({
    code: code as string,
    email: email as string,
    password: password.value,
    password_confirmation: passwordRepeat.value,
  })

  isLoading.value = false
}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";

.reset-password {
  padding: 40px 0;

  .auth-form {
    max-width: 400px;
    margin: 0 auto;
  }
}
</style>
