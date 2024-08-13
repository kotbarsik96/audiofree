<template>
  <div class="login-form">
    <form class="login-form__form auth-form" @submit.prevent="onSubmit">
      <InputWrapper class="auth-form__input" :icon="UserIcon">
        <TextInput v-model="name" placeholder="Ваше имя" />
      </InputWrapper>
      <InputWrapper class="auth-form__input" :icon="MailIcon">
        <TextInput v-model="email" placeholder="Email" />
      </InputWrapper>
      <PasswordInput class="auth-form__input" v-model="password" />
      <PasswordInput class="auth-form__input" v-model="passwordRepeat" placeholder="Пароль еще раз" />
      <div class="auth-form__buttons">
        <AFButton type="submit" label="Войти" />
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

const name = ref("")
const email = ref("")
const password = ref("")
const passwordRepeat = ref("")

function onSubmit() {}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";
</style>
