import { defineStore } from "pinia"
import { ref, watch } from "vue"
import type { authTabs } from "@/enums/auth/authTabs"
import { useNotifications } from "@/composables/useNotifications"

export const useAuthStore = defineStore("auth", () => {
  const tab = ref<authTabs>("signup")
  const previousTab = ref<authTabs>("signup")
  const email = ref("")
  const { addNotification } = useNotifications()

  watch(tab, (_, prevValue) => (previousTab.value = prevValue))

  function goBack() {
    tab.value = previousTab.value
  }
  function sayHello(name: string) {
    addNotification("info", `Здравствуйте, ${name}`, 2500)
  }

  return {
    tab,
    previousTab,
    email,
    goBack,
    sayHello,
  }
})
