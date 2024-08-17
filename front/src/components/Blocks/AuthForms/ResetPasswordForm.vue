<template>
  <form class="reset-form auth-form">
    <InputWrapper class="auth-form__input" :icon="MailIcon">
      <TextInput v-model="email" placeholder="Email" type="email" />
    </InputWrapper>
    <div class="auth-form__buttons">
      <AFButton type="submit" label="Сбросить пароль" />
      <AFButton
        styleType="secondary"
        @click="tab = previousTab"
        label="Вернуться"
      />
    </div>
  </form>
</template>

<script setup lang="ts">
import MailIcon from "@/assets/images/icons/mail.svg"
import InputWrapper from "@/components/Blocks/FormElements/InputWrapper.vue"
import TextInput from "@/components/Blocks/FormElements/TextInput.vue"
import AFButton from "@/components/Blocks/AFButton.vue"
import { useAuthStore } from "@/stores/authStore"
import { storeToRefs } from "pinia"
import { ref } from "vue"

const { previousTab, tab, email } = storeToRefs(useAuthStore())

const emailSent = ref(false)
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";
</style>
