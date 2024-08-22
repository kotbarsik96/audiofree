<template>
  <div class="auth-page">
    <div class="_container">
      <div class="auth-page__box _box">
        <AuthForm class="auth-page__form" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import AuthForm from "@/components/Blocks/AuthForms/AuthForm.vue"
import { useUserStore } from "@/stores/userStore"
import { storeToRefs } from "pinia"
import { watch } from "vue"
import { useRouter } from "vue-router"

const router = useRouter()
const { isAuth } = storeToRefs(useUserStore())

authWatcher()

watch(isAuth, authWatcher)

function authWatcher() {
  if (isAuth.value) router.push({ name: "Home" })
}
</script>

<style lang="scss" scoped>
@import "@/scss/components/_AuthForm";

.auth-page {
  padding: 40px 0;

  &__box {
    overflow: hidden;
    max-width: 30rem;
    width: 100%;
    margin: 0 auto;
    padding: 0;
  }

  &__form {
    align-items: stretch;
  }
}
</style>
