import { LStorageKeys } from "@/enums/LStorageKeys"
import type IUser from "@/services/User/interfaces/response/IUser"
import UserService from "@/services/User/UserService"
import { lStorageSetItem } from "@/utils/lStorage"
import { defineStore } from "pinia"
import { computed, ref, watch } from "vue"

export const useUserStore = defineStore("user", () => {
  const userService = new UserService()

  const token = ref<string>()
  const user = ref<IUser>()
  const isLoading = ref(false)
  const isAuth = computed(() => !!user.value)

  watch(token, () => {
    lStorageSetItem(LStorageKeys.JWT, token.value)
    userService.setTokenHeader()
    getUser()
  })

  async function getUser() {
    isLoading.value = true

    const response = await userService.getUser()
    if (response?.data) user.value = response.data as IUser

    isLoading.value = false
  }

  return {
    token,
    user,
    isAuth,
    isLoading,
    getUser,
  }
})
