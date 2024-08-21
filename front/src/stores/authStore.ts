import { defineStore } from "pinia"
import { ref, watch } from "vue"
import type { authTabs } from "@/enums/auth/authTabs"

export const useAuthStore = defineStore("auth", () => {
  const tab = ref<authTabs>("signup")
  const previousTab = ref<authTabs>("signup")
  const email = ref("")
  const dialogShown = ref(false)

  watch(tab, (_, prevValue) => (previousTab.value = prevValue))

  function goBack() {
    tab.value = previousTab.value
  }

  return {
    tab,
    previousTab,
    email,
    dialogShown,
    goBack,
  }
})
