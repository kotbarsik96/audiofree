<template>
  <div class="login-form">
    <Transition name="fade-in">
      <ResetPasswordForm
        v-if="showResetPassword"
        class="login-form__reset-form"
      />
      <form
        v-else
        class="login-form__form auth-form"
        @submit.prevent="onSubmit"
      >
        <InputWrapper class="auth-form__input" :icon="MailIcon">
          <TextInput v-model="email" placeholder="Email" />
        </InputWrapper>
        <PasswordInput class="auth-form__input" v-model="password" />
        <div class="auth-form__buttons">
          <button class="_link" @click="showResetPassword = true" type="button">
            Забыли пароль?
          </button>
          <AFButton type="submit" label="Войти" />
        </div>
      </form>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import ResetPasswordForm from "@/components/Blocks/AuthForms/ResetPasswordForm.vue"
import InputWrapper from "@/components/Blocks/FormElements/InputWrapper.vue"
import TextInput from "@/components/Blocks/FormElements/TextInput.vue"
import AFButton from "@/components/Blocks/AFButton.vue"
import MailIcon from "@/assets/images/icons/mail.svg"
import PasswordInput from "@/components/Blocks/FormElements/PasswordInput.vue"
import { ref } from "vue"

const showResetPassword = ref(false)
const email = ref("")
const password = ref("")

function onSubmit() {}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";
</style>
