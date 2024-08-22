<template>
  <div>
    <Transition name="fade-in" mode="out-in">
      <form v-if="!emailSentMessage" class="reset-form auth-form">
        <InputWrapper class="auth-form__input" :icon="MailIcon">
          <TextInput v-model="email" placeholder="Email" type="email" />
        </InputWrapper>
        <div class="auth-form__buttons">
          <AFButton
            type="submit"
            label="Сбросить пароль"
            :disabled="isLoading"
            @click="send"
          />
          <AFButton
            styleType="secondary"
            label="Вернуться"
            :disabled="isLoading"
            @click="tab = previousTab"
          />
        </div>
      </form>
      <div v-else class="email-sent">
        {{ emailSentMessage }}
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import MailIcon from "@/assets/images/icons/mail.svg"
import InputWrapper from "@/components/Blocks/FormElements/InputWrapper.vue"
import TextInput from "@/components/Blocks/FormElements/TextInput.vue"
import AFButton from "@/components/Blocks/AFButton.vue"
import { useAuthStore } from "@/stores/authStore"
import { storeToRefs } from "pinia"
import { ref } from "vue"
import UserService from "@/services/User/UserService"

const userService = new UserService()
const { previousTab, tab, email } = storeToRefs(useAuthStore())

const emailSentMessage = ref()
const isLoading = ref(false)

async function send() {
  isLoading.value = true

  const response = await userService.sendPasswordResetLink({
    email: email.value,
  })
  if (response?.payload.message)
    emailSentMessage.value = response.payload.message

  isLoading.value = false
}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";

.email-sent {
  text-align: center;
  margin: 30px auto;
  color: var(--primary);
  @include fHeadline(2);
}
</style>
