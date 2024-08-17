<template>
  <form class="login-form auth-form" @submit.prevent="onSubmit">
    <InputWrapper class="auth-form__input" type="email" :icon="MailIcon">
      <TextInput v-model="email" placeholder="Email" />
    </InputWrapper>
    <PasswordInput class="auth-form__input" v-model="password" />
    <div class="auth-form__buttons">
      <button class="_link" @click="tab = 'reset'" type="button">
        Забыли пароль?
      </button>
      <AFButton type="submit" label="Войти" />
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
import { useAuthStore } from "@/stores/authStore"

const { tab, email } = storeToRefs(useAuthStore())

const password = ref("")

function onSubmit() {}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";

.login-form {
  min-height: 12rem;
}
</style>
