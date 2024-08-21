import { useNotifications } from "@/composables/useNotifications"
import { LStorageKeys } from "@/enums/LStorageKeys"
import type IUser from "@/services/User/interfaces/response/IUser"
import UserService from "@/services/User/UserService"
import { lStorageGetItem, lStorageSetItem } from "@/utils/lStorage"
import { defineStore } from "pinia"
import { computed, ref, watch } from "vue"

export const useUserStore = defineStore("user", () => {
  const userService = new UserService()
  const { addNotification } = useNotifications()

  const token = ref<string>(lStorageGetItem(LStorageKeys.JWT))
  const user = ref<IUser>()
  const isLoading = ref(false)
  const isAuth = computed(() => !!user.value)

  watch(token, () => {
    lStorageSetItem(LStorageKeys.JWT, token.value)
    userService.setTokenHeader()
    getUser()
  })

  async function getUser() {
    if (!!token.value) {
      isLoading.value = true

      const response = await userService.getUser()
      if (response?.payload) {
        user.value = response.payload as IUser
      } else if (!response) {
        token.value = ""
      }

      isLoading.value = false
    }
  }
  async function logout() {
    isLoading.value = true

    const response = await userService.logout()
    token.value = ""
    user.value = undefined
    if (response?.payload?.message)
      addNotification("info", response.payload.message)

    isLoading.value = false
  }

  return {
    token,
    user,
    isAuth,
    isLoading,
    getUser,
    logout,
  }
})
